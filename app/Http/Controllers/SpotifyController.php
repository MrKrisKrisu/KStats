<?php

namespace App\Http\Controllers;

use App\Exceptions\SpotifyAPIException;
use App\Exceptions\SpotifyTokenExpiredException;
use App\SocialLoginProfile;
use App\SpotifyArtist;
use App\SpotifyPlayActivity;
use App\SpotifySession;
use App\SpotifyTrack;
use App\User;
use App\UserSettings;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpotifyController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        if (!isset(auth()->user()->socialProfile->spotify_accessToken) || auth()->user()->socialProfile->spotify_accessToken == null)
            return view('spotify.notconnected');

        if (auth()->user()->spotifyActivity()->count() == 0)
            return view('spotify.nodata');

        $chartDataHearedByWeek = auth()->user()->spotifyActivity()
                                       ->select(DB::raw('YEAR(created_at) AS year'), DB::raw('WEEK(created_at) AS week'), DB::raw('COUNT(*) as minutes'))
                                       ->groupBy('year', 'week')
                                       ->orderBy('year', 'asc')
                                       ->orderBy('week', 'asc')
                                       ->get();

        $chartDataHearedByWeekday = auth()->user()->spotifyActivity()
                                          ->select(DB::raw('WEEKDAY(created_at) AS weekday'), DB::raw('COUNT(*) as minutes'))
                                          ->groupBy(DB::raw('weekday'))
                                          ->orderBy('weekday', 'asc')
                                          ->get();

        $chartDataHearedByHour = auth()->user()->spotifyActivity()
                                       ->select(DB::raw('HOUR(created_at) AS hour'), DB::raw('COUNT(*) as minutes'))
                                       ->groupBy(DB::raw('hour'))
                                       ->orderBy('hour', 'asc')
                                       ->get();

        $topTracks = auth()->user()->spotifyActivity()->with(['track', 'track.album', 'track.artists'])
                           ->groupBy('track_id')
                           ->select('track_id', DB::raw('COUNT(*) as minutes'))
                           ->orderBy('minutes', 'DESC')
                           ->paginate(3);

        $topTracks30 = auth()->user()->spotifyActivity()->with(['track', 'track.album', 'track.artists'])
                             ->where('timestamp_start', '>', Carbon::parse('-30 days'))
                             ->groupBy('track_id')
                             ->select('track_id', DB::raw('COUNT(*) as minutes'))
                             ->orderBy('minutes', 'DESC')
                             ->paginate(3);

        return view('spotify.spotify', [
            'topTracksTotal'            => $topTracks,
            'topTracks30'               => $topTracks30,
            'chartData_hearedByWeek'    => $chartDataHearedByWeek,
            'chartData_hearedByWeekday' => $chartDataHearedByWeekday,
            'chartData_hearedByHour'    => $chartDataHearedByHour
        ]);
    }

    /**
     * Show the users top tracks
     *
     * @param String $term expect 'long_term', 'medium_term' or 'short_term'
     * @return Renderable
     */
    public function topTracks(string $term = 'long_term'): Renderable
    {
        $socialProfile = auth()->user()->socialProfile()->first() ?: new SocialLoginProfile;
        if ($socialProfile->spotify_accessToken == null)
            return view('spotify.notconnected');

        $dataCount = User::find(auth()->user()->id)->spotifyActivity->count();
        if ($dataCount == 0)
            return view('spotify.nodata');

        if (!in_array($term, ['long_term', 'short_term', 'medium_term']))
            $term = 'short_term';

        $access_token = auth()->user()->socialProfile->spotify_accessToken;

        try {
            $top_tracks = SpotifyAPIController::getTopTracks($access_token, $term);
        } catch (SpotifyTokenExpiredException $e) {
            return view('spotify.notconnected');
        } catch (SpotifyAPIException $e) {
            report($e);
            return view('spotify.nodata');
        }

        return view('spotify.top_tracks', [
            'top_tracks' => $top_tracks
        ]);
    }

    /**
     * Show the users lost tracks
     *
     * @return Renderable
     */
    public function lostTracks(): Renderable
    {
        $socialProfile = auth()->user()->socialProfile()->first() ?: new SocialLoginProfile;
        if ($socialProfile->spotify_accessToken == null)
            return view('spotify.notconnected');

        $dataCount = User::find(auth()->user()->id)->spotifyActivity->count();
        if ($dataCount == 0)
            return view('spotify.nodata');

        $settings_minutes = UserSettings::get(auth()->user()->id, 'spotify_oldPlaylist_minutesTop', '120');
        $settings_days    = UserSettings::get(auth()->user()->id, 'spotify_oldPlaylist_days', '30');
        $settings_limit   = UserSettings::get(auth()->user()->id, 'spotify_oldPlaylist_songlimit', '30');

        $lostTracks = self::getLikedAndNotHearedTracks(Auth::user()->id, $settings_limit, $settings_days, $settings_minutes);

        return view('spotify.lost_tracks', [
            'settings_active'  => UserSettings::get(Auth::user()->id, 'spotify_createOldPlaylist', '0'),
            'playlist_id'      => UserSettings::get(Auth::user()->id, 'spotify_oldPlaylist_id'),
            'settings_minutes' => $settings_minutes,
            'settings_days'    => $settings_days,
            'settings_limit'   => $settings_limit,
            'lostTracks'       => $lostTracks
        ]);
    }

    /**
     * @param Request $request
     * @return Renderable
     */
    public function saveLostTracks(Request $request): Renderable
    {
        $validated = $request->validate([
                                            'spotify_createOldPlaylist'      => [],
                                            'spotify_oldPlaylist_minutesTop' => ['required', 'integer', 'min:1'],
                                            'spotify_oldPlaylist_days'       => ['required', 'integer', 'min:1'],
                                            'spotify_oldPlaylist_songlimit'  => ['required', 'integer', 'max:99'],
                                        ]);

        UserSettings::set(auth()->user()->id, 'spotify_createOldPlaylist', isset($validated['spotify_createOldPlaylist']));
        UserSettings::set(auth()->user()->id, 'spotify_oldPlaylist_minutesTop', $validated['spotify_oldPlaylist_minutesTop']);
        UserSettings::set(auth()->user()->id, 'spotify_oldPlaylist_days', $validated['spotify_oldPlaylist_days']);
        UserSettings::set(auth()->user()->id, 'spotify_oldPlaylist_songlimit', $validated['spotify_oldPlaylist_songlimit']);

        return $this->lostTracks();
    }

    public static function getLikedAndNotHearedTracks(int $userId, int $limit = 30, int $days = 90, int $minHearedMinutes = 30): Collection
    {

        $tracksByMinutes = SpotifyPlayActivity::where('user_id', $userId)
                                              ->groupBy('track_id')
                                              ->havingRaw('COUNT(*) > ?', [$minHearedMinutes])
                                              ->select('track_id')
                                              ->get();

        $tracksByTime = SpotifyPlayActivity::where('user_id', $userId)
                                           ->groupBy('track_id')
                                           ->havingRaw('MAX(created_at) < (NOW() - INTERVAL ? DAY)', [$days])
                                           ->select('track_id')
                                           ->get();

        $tracksMin = [];
        foreach ($tracksByMinutes as $t)
            $tracksMin[] = $t->track_id;

        $tracksTime = [];
        foreach ($tracksByTime as $t)
            $tracksTime[] = $t->track_id;

        $trackList = [];
        foreach ($tracksMin as $t1)
            foreach ($tracksTime as $t2)
                if ($t1 == $t2 && !in_array($t1, $trackList) && count($trackList) < $limit)
                    $trackList[] = $t1;

        return SpotifyTrack::with(['artists', 'album'])
                           ->whereIn('track_id', $trackList)
                           ->orderBy('popularity', 'desc')
                           ->limit(UserSettings::get($userId, 'spotify_oldPlaylist_songlimit', 99))
                           ->get();

    }

    /**
     * @param User $user
     * @return bool
     * @throws SpotifyTokenExpiredException
     * TODO: Exceptions handlen
     */
    public static function generateLostPlaylist(User $user)
    {
        $settingsMinutes = UserSettings::get($user->id, 'spotify_oldPlaylist_minutesTop', '120');
        $settingsDays    = UserSettings::get($user->id, 'spotify_oldPlaylist_days', '30');
        $settingsLimit   = UserSettings::get($user->id, 'spotify_oldPlaylist_songlimit', '30');

        $lostTracks = SpotifyController::getLikedAndNotHearedTracks($user->id, $settingsLimit, $settingsDays, $settingsMinutes);

        //me
        $client = new Client();
        $result = $client->get('https://api.spotify.com/v1/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $user->socialProfile->spotify_accessToken
            ]
        ]);

        if ($result->getStatusCode() == 401)
            throw new SpotifyTokenExpiredException();

        if ($result->getStatusCode() != 200)
            return false;

        $data            = json_decode($result->getBody()->getContents());
        $spotify_user_id = $data->id;

        $playlistID = UserSettings::get($user->id, 'spotify_oldPlaylist_id');
        if ($playlistID == null) {
            //Playlist erstellen
            $client = new Client();
            $result = $client->post('https://api.spotify.com/v1/users/' . $spotify_user_id . '/playlists', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $user->socialProfile->spotify_accessToken,
                    'Content-Type'  => 'application/json'
                ],
                'body'    => json_encode([
                                             'name'        => 'Meine verschollenen Tracks',
                                             'description' => 'Meine verschollenen Tracks - generiert von KStats auf k118.de',
                                             'public'      => false
                                         ])
            ]);

            $data = json_decode($result->getBody()->getContents());

            $playlistID = $data->id;
            UserSettings::set($user->id, 'spotify_oldPlaylist_id', $playlistID);
        }

        $tracks = [];
        foreach ($lostTracks as $t)
            $tracks[] = 'spotify:track:' . $t->track_id;


        //Playlist füllen
        $client = new Client();
        $result = $client->put('https://api.spotify.com/v1/playlists/' . $playlistID . '/tracks', [
            'headers' => [
                'Authorization' => 'Bearer ' . $user->socialProfile->spotify_accessToken,
                'Content-Type'  => 'application/json'
            ],
            'body'    => json_encode([
                                         'uris' => $tracks
                                     ])
        ]);

        $data = json_decode($result->getBody()->getContents());

        dump($data);
    }

    public static function getWeekdayName(int $weekday)
    {
        $list = [
            0 => __('general.weekday.mon'),
            1 => __('general.weekday.tue'),
            2 => __('general.weekday.wed'),
            3 => __('general.weekday.thu'),
            4 => __('general.weekday.fri'),
            5 => __('general.weekday.sat'),
            6 => __('general.weekday.sun'),
        ];
        return $list[$weekday] ?? $weekday;
    }

    public function trackDetails($trackId)
    {
        $track              = SpotifyTrack::findOrFail($trackId);
        $listeningDaysQuery = SpotifyPlayActivity::where('user_id', Auth::user()->id)
                                                 ->where('track_id', $track->track_id)
                                                 ->groupBy(DB::raw('DATE(created_at)'))
                                                 ->select(DB::raw('DATE(created_at) AS date'), DB::raw('COUNT(*) AS minutes'))
                                                 ->orderBy(DB::raw('DATE(created_at)'))
                                                 ->get();

        $listeningDays = [];
        foreach ($listeningDaysQuery as $ld) {
            $listeningDays[$ld->date]          = new \stdClass();
            $listeningDays[$ld->date]->date    = $ld->date;
            $listeningDays[$ld->date]->minutes = $ld->minutes;
        }
        if (count($listeningDays) > 0) {
            $date = Carbon::parse(array_values($listeningDays)[0]->date);
            while ($date->isPast()) {
                $date = $date->addDays(1);
                if (!isset($listeningDays[$date->format('Y-m-d')])) {
                    $listeningDays[$date->format('Y-m-d')]          = new \stdClass();
                    $listeningDays[$date->format('Y-m-d')]->date    = $date->isoFormat('YYYY-MM-DD');
                    $listeningDays[$date->format('Y-m-d')]->minutes = 0;
                }
            }
        }
        ksort($listeningDays);

        return view('spotify.track_details', [
            'track'          => $track,
            'listening_days' => $listeningDays
        ]);
    }

    public function getFavouriteYear()
    {
        $favouriteYearQ = SpotifyPlayActivity::where('user_id', auth()->user()->id)
                                             ->join('spotify_tracks', 'spotify_tracks.track_id', '=', 'spotify_play_activities.track_id')
                                             ->join('spotify_albums', 'spotify_tracks.album_id', '=', 'spotify_albums.album_id')
                                             ->where('spotify_albums.release_date', '<>', null)
                                             ->groupBy('release_year')
                                             ->select(DB::raw('YEAR(spotify_albums.release_date) as release_year'))
                                             ->orderBy(DB::raw('COUNT(*)'), 'desc')
                                             ->first();

        return $favouriteYearQ == null ? '?' : $favouriteYearQ->release_year;
    }

    public function getAverageBPM()
    {
        $bpm = SpotifyPlayActivity::where('user_id', auth()->user()->id)
                                  ->join('spotify_tracks', 'spotify_tracks.track_id', '=', 'spotify_play_activities.track_id')
                                  ->select(DB::raw('AVG(bpm) as bpm'))
                                  ->first()->bpm;
        return round($bpm);
    }

    public function getAverageSessionLength()
    {
        $avgSession = SpotifySession::where('user_id', auth()->user()->id)
                                    ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, timestamp_start, timestamp_end)) as sessionTime'))
                                    ->first()->sessionTime;
        return round($avgSession);
    }

    public function getTrackCount()
    {
        return SpotifyPlayActivity::where('user_id', auth()->user()->id)->groupBy('track_id')->select('track_id')->get()->count();
    }

    public function getTopArtists($timeFrom = null, $timeTo = null, int $limit = 5)
    {
        $query = SpotifyArtist::join('spotify_track_artists', 'spotify_track_artists.artist_id', '=', 'spotify_artists.id')
                              ->join('spotify_tracks', 'spotify_tracks.id', '=', 'spotify_track_artists.track_id')
                              ->join('spotify_play_activities', 'spotify_play_activities.track_id', '=', 'spotify_tracks.track_id')
                              ->where('spotify_play_activities.user_id', auth()->user()->id)
                              ->groupBy('spotify_artists.id')
                              ->select('spotify_artists.*', DB::raw('COUNT(*) AS minutes'))
                              ->orderBy('minutes', 'desc')
                              ->limit($limit);

        if ($timeTo != null) {
            $timeTo = Carbon::parse($timeTo);
            $query->where('spotify_play_activities.created_at', '<=', $timeTo);
        }
        if ($timeFrom != null) {
            $timeFrom = Carbon::parse($timeFrom);
            $query->where('spotify_play_activities.created_at', '>=', $timeFrom);
        }

        return $query->get();
    }

    public function getPlaytime($timeFrom = null, $timeTo = null)
    {
        $query = SpotifyPlayActivity::where('user_id', auth()->user()->id);

        if ($timeFrom != null) {
            $timeFrom = Carbon::parse($timeFrom);
            $query->where('created_at', '>=', $timeFrom);
        }
        if ($timeTo != null) {
            $timeTo = Carbon::parse($timeTo);
            $query->where('created_at', '<=', $timeTo);
        }

        return $query->count();
    }

    public function getTopTracks($timeFrom = null, $timeTo = null, int $limit = 3)
    {
        $query = SpotifyPlayActivity::with(['track', 'track.album', 'track.artists'])
                                    ->where('user_id', auth()->user()->id)
                                    ->groupBy('track_id')
                                    ->select('track_id', DB::raw('COUNT(*) as minutes'))
                                    ->orderBy('minutes', 'DESC')
                                    ->limit($limit);

        if ($timeTo != null) {
            $timeTo = Carbon::parse($timeTo);
            $query->where('created_at', '<=', $timeTo);
        }
        if ($timeFrom != null) {
            $timeFrom = Carbon::parse($timeFrom);
            $query->where('created_at', '>=', $timeFrom);
        }

        return $query->get();
    }

    public function getLastPlayed()
    {
        return SpotifyPlayActivity::with(['track', 'track.album', 'track.artists'])
                                  ->where('user_id', auth()->user()->id)
                                  ->orderBy('created_at', 'desc')
                                  ->limit(1)
                                  ->first();
    }

    public function renderDailyHistory(Request $request, $date = null)
    {
        if ($date == null) $date = Carbon::today();
        else $date = Carbon::parse($date);

        if ($date->isAfter(Carbon::now())) {
            $request->session()->flash('alert-danger', __('general.error.future_not_possible'));
            return redirect()->route('spotify.history');
        }

        $history = SpotifyPlayActivity::with(['track', 'device'])
                                      ->where('user_id', Auth::user()->id)
                                      ->where('created_at', '>=', $date->toDateString() . ' 00:00:00')
                                      ->where('created_at', '<=', $date->toDateString() . ' 23:59:59')
                                      ->orderBy('timestamp_start')
                                      ->paginate(10);

        return view('spotify.daily_history', [
            'date'    => $date,
            'history' => $history
        ]);
    }

}
