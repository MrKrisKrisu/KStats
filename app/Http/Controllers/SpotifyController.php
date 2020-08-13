<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $socialProfile = auth()->user()->socialProfile()->first() ?: new SocialLoginProfile;
        if ($socialProfile->spotify_accessToken == null)
            return view('spotify.notconnected');

        $dataCount = User::find(auth()->user()->id)->spotifyActivity->count();
        if ($dataCount == 0)
            return view('spotify.nodata');

        $chartData_hearedByWeek = SpotifyPlayActivity::where('user_id', auth()->user()->id)
            ->select(DB::raw('YEAR(created_at) AS year'), DB::raw('WEEK(created_at) AS week'), DB::raw('COUNT(*) as minutes'))
            ->groupBy('year', 'week')
            ->orderBy('year', 'asc')
            ->orderBy('week', 'asc')
            ->get();

        $chartData_hearedByWeekday = SpotifyPlayActivity::where('user_id', auth()->user()->id)
            ->select(DB::raw('WEEKDAY(created_at) AS weekday'), DB::raw('COUNT(*) as minutes'))
            ->groupBy(DB::raw('weekday'))
            ->orderBy('weekday', 'asc')
            ->get();

        $chartData_hearedByHour = SpotifyPlayActivity::where('user_id', auth()->user()->id)
            ->select(DB::raw('HOUR(created_at) AS hour'), DB::raw('COUNT(*) as minutes'))
            ->groupBy(DB::raw('hour'))
            ->orderBy('hour', 'asc')
            ->get();

        return view('spotify.spotify', [
            'chartData_hearedByWeek' => $chartData_hearedByWeek,
            'chartData_hearedByWeekday' => $chartData_hearedByWeekday,
            'chartData_hearedByHour' => $chartData_hearedByHour
        ]);
    }

    /**
     * Show the users top tracks
     *
     * @param String $term expect 'long_term', 'medium_term' or 'short_term'
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function topTracks(string $term = 'long_term')
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
        }

        return view('spotify.top_tracks', [
            'top_tracks' => $top_tracks
        ]);
    }

    /**
     * Show the users lost tracks
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function lostTracks()
    {
        $socialProfile = auth()->user()->socialProfile()->first() ?: new SocialLoginProfile;
        if ($socialProfile->spotify_accessToken == null)
            return view('spotify.notconnected');

        $dataCount = User::find(auth()->user()->id)->spotifyActivity->count();
        if ($dataCount == 0)
            return view('spotify.nodata');

        $settings_minutes = UserSettings::get(auth()->user()->id, 'spotify_oldPlaylist_minutesTop', '120');
        $settings_days = UserSettings::get(auth()->user()->id, 'spotify_oldPlaylist_days', '30');
        $settings_limit = UserSettings::get(auth()->user()->id, 'spotify_oldPlaylist_songlimit', '30');

        $lostTracks = self::getLikedAndNotHearedTracks(Auth::user()->id, $settings_limit, $settings_days, $settings_minutes);

        return view('spotify.lost_tracks', [
            'settings_active' => UserSettings::get(Auth::user()->id, 'spotify_createOldPlaylist', '0'),
            'playlist_id' => UserSettings::get(Auth::user()->id, 'spotify_oldPlaylist_id'),
            'settings_minutes' => $settings_minutes,
            'settings_days' => $settings_days,
            'settings_limit' => $settings_limit,
            'lostTracks' => $lostTracks
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function saveLostTracks(Request $request)
    {
        $validated = $request->validate([
            'spotify_createOldPlaylist' => [],
            'spotify_oldPlaylist_minutesTop' => ['required', 'integer', 'min:1'],
            'spotify_oldPlaylist_days' => ['required', 'integer', 'min:1'],
            'spotify_oldPlaylist_songlimit' => ['required', 'integer', 'max:99'],
        ]);

        UserSettings::set(auth()->user()->id, 'spotify_createOldPlaylist', isset($validated['spotify_createOldPlaylist']));
        UserSettings::set(auth()->user()->id, 'spotify_oldPlaylist_minutesTop', $validated['spotify_oldPlaylist_minutesTop']);
        UserSettings::set(auth()->user()->id, 'spotify_oldPlaylist_days', $validated['spotify_oldPlaylist_days']);
        UserSettings::set(auth()->user()->id, 'spotify_oldPlaylist_songlimit', $validated['spotify_oldPlaylist_songlimit']);

        return $this->lostTracks();
    }

    public static function getLikedAndNotHearedTracks(int $user_id, int $limit = 30, int $days = 90, int $minHearedMinutes = 30)
    {

        $tracksByMinutes = SpotifyPlayActivity::where('user_id', $user_id)
            ->groupBy('track_id')
            ->havingRaw('COUNT(*) > ?', [$minHearedMinutes])
            ->select('track_id')
            ->get();

        $tracksByTime = SpotifyPlayActivity::where('user_id', $user_id)
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
            ->limit(UserSettings::get($user_id, 'spotify_oldPlaylist_songlimit', 99))
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
        $settings_minutes = UserSettings::get($user->id, 'spotify_oldPlaylist_minutesTop', '120');
        $settings_days = UserSettings::get($user->id, 'spotify_oldPlaylist_days', '30');
        $settings_limit = UserSettings::get($user->id, 'spotify_oldPlaylist_songlimit', '30');

        $lostTracks = SpotifyController::getLikedAndNotHearedTracks($user->id, $settings_limit, $settings_days, $settings_minutes);


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

        $data = json_decode($result->getBody()->getContents());
        $spotify_user_id = $data->id;

        $playlistID = UserSettings::get($user->id, 'spotify_oldPlaylist_id');
        if ($playlistID == NULL) {
            //Playlist erstellen
            $client = new Client();
            $result = $client->post('https://api.spotify.com/v1/users/' . $spotify_user_id . '/playlists', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $user->socialProfile->spotify_accessToken,
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode([
                    'name' => 'Meine verschollenen Tracks',
                    'description' => 'Meine verschollenen Tracks - generiert von KStats auf k118.de',
                    'public' => false
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
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'uris' => $tracks
            ])
        ]);

        $data = json_decode($result->getBody()->getContents());

        dump($data);
    }

    public static function getWeekdayName(int $wd)
    {
        $a = [
            0 => __('general.weekday.mon'),
            1 => __('general.weekday.tue'),
            2 => __('general.weekday.wed'),
            3 => __('general.weekday.thu'),
            4 => __('general.weekday.fri'),
            5 => __('general.weekday.sat'),
            6 => __('general.weekday.sun'),
        ];
        return $a[$wd] ?? $wd;
    }

    public function trackDetails($id)
    {
        $track = SpotifyTrack::findOrFail($id);
        $listening_days_query = SpotifyPlayActivity::where('user_id', Auth::user()->id)
            ->where('track_id', $track->track_id)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->select(DB::raw('DATE(created_at) AS date'), DB::raw('COUNT(*) AS minutes'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        $listening_days = [];
        foreach ($listening_days_query as $ld) {
            $listening_days[$ld->date] = new \stdClass();
            $listening_days[$ld->date]->date = $ld->date;
            $listening_days[$ld->date]->minutes = $ld->minutes;
        }
        if (count($listening_days) > 0) {
            $date = Carbon::parse(array_values($listening_days)[0]->date);
            while ($date->isPast()) {
                $date = $date->addDays(1);
                if (!isset($listening_days[$date->isoFormat('YYYY-MM-DD')])) {
                    $listening_days[$date->isoFormat('YYYY-MM-DD')] = new \stdClass();
                    $listening_days[$date->isoFormat('YYYY-MM-DD')]->date = $date->isoFormat('YYYY-MM-DD');
                    $listening_days[$date->isoFormat('YYYY-MM-DD')]->minutes = 0;
                }
            }
        }
        ksort($listening_days);

        return view('spotify.track_details', [
            'track' => $track,
            'listening_days' => $listening_days
        ]);
    }

    public function getFavouriteYear()
    {
        $favouriteYear_q = SpotifyPlayActivity::where('user_id', auth()->user()->id)
            ->join('spotify_tracks', 'spotify_tracks.track_id', '=', 'spotify_play_activities.track_id')
            ->join('spotify_albums', 'spotify_tracks.album_id', '=', 'spotify_albums.album_id')
            ->where('spotify_albums.release_date', '<>', null)
            ->groupBy('release_year')
            ->select(DB::raw('YEAR(spotify_albums.release_date) as release_year'))
            ->orderBy(DB::raw('COUNT(*)'), 'desc')
            ->first();

        return $favouriteYear_q == NULL ? '?' : $favouriteYear_q->release_year;
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

    public function getTopArtists($time_from = NULL, $time_to = NULL, int $limit = 5)
    {
        $q = SpotifyArtist::join('spotify_track_artists', 'spotify_track_artists.artist_id', '=', 'spotify_artists.id')
            ->join('spotify_tracks', 'spotify_tracks.id', '=', 'spotify_track_artists.track_id')
            ->join('spotify_play_activities', 'spotify_play_activities.track_id', '=', 'spotify_tracks.track_id')
            ->where('spotify_play_activities.user_id', auth()->user()->id)
            ->groupBy('spotify_artists.id')
            ->select('spotify_artists.*', DB::raw('COUNT(*) AS minutes'))
            ->orderBy('minutes', 'desc')
            ->limit($limit);

        if ($time_to != NULL) {
            $time_to = Carbon::parse($time_to);
            $q->where('spotify_play_activities.created_at', '<=', $time_to);
        }
        if ($time_from != NULL) {
            $time_from = Carbon::parse($time_from);
            $q->where('spotify_play_activities.created_at', '>=', $time_from);
        }

        return $q->get();
    }

    public function getPlaytime($time_from = NULL, $time_to = NULL)
    {
        $q = SpotifyPlayActivity::where('user_id', auth()->user()->id);

        if ($time_from != NULL) {
            $time_from = Carbon::parse($time_from);
            $q->where('created_at', '>=', $time_from);
        }
        if ($time_to != NULL) {
            $time_to = Carbon::parse($time_to);
            $q->where('created_at', '<=', $time_to);
        }

        return $q->count();
    }

    public function getTopTracks($time_from = NULL, $time_to = NULL, int $limit = 3)
    {
        $q = SpotifyPlayActivity::with(['track', 'track.album', 'track.artists'])
            ->where('user_id', auth()->user()->id)
            ->groupBy('track_id')
            ->select('track_id', DB::raw('COUNT(*) as minutes'))
            ->orderBy('minutes', 'DESC')
            ->limit($limit);

        if ($time_to != NULL) {
            $time_to = Carbon::parse($time_to);
            $q->where('created_at', '<=', $time_to);
        }
        if ($time_from != NULL) {
            $time_from = Carbon::parse($time_from);
            $q->where('created_at', '>=', $time_from);
        }

        return $q->get();
    }

    public function getLastPlayed()
    {
        return SpotifyPlayActivity::with(['track', 'track.album', 'track.artists'])
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();
    }

    public function renderDailyHistory(Request $request, $date = NULL)
    {
        if ($date == NULL) $date = Carbon::today();
        else $date = Carbon::parse($date);

        if ($date->isAfter(Carbon::now())) {
            $request->session()->flash('alert-danger', __('general.error.future_not_possible'));
            return redirect()->route('spotify.history');
        }

        $history = SpotifyPlayActivity::where('user_id', Auth::user()->id)
            ->whereDate('created_at', $date->toDateString())
            ->get();

        return view('spotify.daily_history', [
            'date' => $date,
            'history' => $history
        ]);
    }

}
