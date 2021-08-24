<?php

namespace App\Http\Controllers;

use App\Exceptions\SpotifyTokenExpiredException;
use App\Models\SocialLoginProfile;
use App\Models\SpotifyArtist;
use App\Models\SpotifyPlayActivity;
use App\Models\SpotifySession;
use App\Models\SpotifyTrack;
use App\Models\SpotifyTrackRating;
use App\Models\User;
use App\Models\UserSettings;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SpotifyController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(): View {
        if(!isset(auth()->user()->socialProfile->spotify_accessToken) || auth()->user()->socialProfile->spotify_accessToken == null) {
            return view('spotify.notconnected');
        }

        if(auth()->user()->spotifyActivity()->count() == 0) {
            return view('spotify.nodata');
        }

        $chartDataHearedByWeek = auth()->user()->spotifyActivity()
                                       ->select(DB::raw('YEAR(created_at) AS year'), DB::raw('WEEK(created_at) AS week'), DB::raw('SUM(duration) / 60 as minutes'))
                                       ->groupBy('year', 'week')
                                       ->orderBy('year', 'asc')
                                       ->orderBy('week', 'asc')
                                       ->get();

        $chartDataHearedByWeekday = auth()->user()->spotifyActivity()
                                          ->select(DB::raw('WEEKDAY(created_at) AS weekday'), DB::raw('SUM(duration) / 60 as minutes'))
                                          ->groupBy(DB::raw('weekday'))
                                          ->orderBy('weekday', 'asc')
                                          ->get();

        $chartDataHearedByHour = auth()->user()->spotifyActivity()
                                       ->select(DB::raw('HOUR(created_at) AS hour'), DB::raw('SUM(duration) / 60 as minutes'))
                                       ->groupBy(DB::raw('hour'))
                                       ->orderBy('hour', 'asc')
                                       ->get();

        $topTracks = auth()->user()->spotifyActivity()->with(['track', 'track.album', 'track.artists'])
                           ->groupBy('track_id')
                           ->select('track_id', DB::raw('SUM(duration) / 60 as minutes'))
                           ->orderBy('minutes', 'DESC')
                           ->paginate(3);

        $topTracks30 = auth()->user()->spotifyActivity()->with(['track', 'track.album', 'track.artists'])
                             ->where('timestamp_start', '>', Carbon::parse('-30 days'))
                             ->groupBy('track_id')
                             ->select('track_id', DB::raw('SUM(duration) / 60 as minutes'))
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

    public function topTracks(Request $request): View {
        $socialProfile = auth()->user()->socialProfile()->first() ?: new SocialLoginProfile;
        if($socialProfile->spotify_accessToken == null) {
            return view('spotify.notconnected');
        }

        $validated = $request->validate([
                                            'from' => ['nullable', 'date'],
                                            'to'   => ['nullable', 'date'],
                                        ]);

        $topTracks = auth()->user()->spotifyActivity();
        if(isset($validated['from'])) {
            $topTracks->where('created_at', '>=', $validated['from']);
        }
        if(isset($validated['to'])) {
            $topTracks->where('created_at', '<=', $validated['to']);
        }
        $topTracks->groupBy('track_id')
                  ->select(['track_id', DB::raw('SUM(duration) / 60 as minutes')])
                  ->orderByDesc('minutes');

        return view('spotify.top_tracks', [
            'top_tracks' => $topTracks->paginate(16),
            'from'       => isset($validated['from']) ? Carbon::parse($validated['from']) : auth()->user()->spotifyActivity()->orderBy('created_at')->limit(1)->select('created_at')->first()->created_at,
            'to'         => isset($validated['to']) ? Carbon::parse($validated['to']) : auth()->user()->spotifyActivity()->orderBy('created_at', 'DESC')->limit(1)->select('created_at')->first()->created_at
        ]);
    }

    public function lostTracks(): View {
        $socialProfile = auth()->user()->socialProfile()->first() ?: new SocialLoginProfile;
        if($socialProfile->spotify_accessToken == null) {
            return view('spotify.notconnected');
        }

        $dataCount = User::find(auth()->user()->id)->spotifyActivity->count();
        if($dataCount == 0) {
            return view('spotify.nodata');
        }

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

    public function saveLostTracks(Request $request): RedirectResponse {
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

        return back();
    }

    public static function getLikedAndNotHearedTracks(int $userId, int $limit = 30, int $days = 90, int $minHearedMinutes = 30): Collection {

        $tracksByMinutes = SpotifyPlayActivity::where('user_id', $userId)
                                              ->groupBy('track_id')
                                              ->havingRaw('(SUM(duration) / 60) > ?', [$minHearedMinutes])
                                              ->select('track_id')
                                              ->get();

        $tracksByTime = SpotifyPlayActivity::where('user_id', $userId)
                                           ->groupBy('track_id')
                                           ->havingRaw('MAX(created_at) < (NOW() - INTERVAL ? DAY)', [$days])
                                           ->select('track_id')
                                           ->get();

        $tracksMin = [];
        foreach($tracksByMinutes as $t) {
            $tracksMin[] = $t->track_id;
        }

        $tracksTime = [];
        foreach($tracksByTime as $t) {
            $tracksTime[] = $t->track_id;
        }

        $trackList = [];
        foreach($tracksMin as $t1) {
            foreach($tracksTime as $t2) {
                if($t1 == $t2 && !in_array($t1, $trackList) && count($trackList) < $limit) {
                    $trackList[] = $t1;
                }
            }
        }

        return SpotifyTrack::with(['artists', 'album'])
                           ->whereIn('track_id', $trackList)
                           ->orderBy('popularity', 'desc')
                           ->limit(UserSettings::get($userId, 'spotify_oldPlaylist_songlimit', 99))
                           ->get();
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws SpotifyTokenExpiredException
     * TODO: Exceptions handlen
     */
    public static function generateLostPlaylist(User $user) {
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

        if($result->getStatusCode() == 401)
            throw new SpotifyTokenExpiredException();

        if($result->getStatusCode() != 200)
            return false;

        $data            = json_decode($result->getBody()->getContents());
        $spotify_user_id = $data->id;

        $playlistID = UserSettings::get($user->id, 'spotify_oldPlaylist_id');
        if($playlistID == null) {
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
        foreach($lostTracks as $t)
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

    /**
     * @param int $weekday
     *
     * @return int|mixed
     * @deprecated use carbon
     */
    public static function getWeekdayName(int $weekday) {
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

    public function trackDetails($trackId): View {
        $track              = SpotifyTrack::findOrFail($trackId);
        $listeningDaysQuery = SpotifyPlayActivity::where('user_id', Auth::user()->id)
                                                 ->where('track_id', $track->track_id)
                                                 ->groupBy(DB::raw('DATE(created_at)'))
                                                 ->select(DB::raw('DATE(created_at) AS date'), DB::raw('SUM(duration) / 60 AS minutes'))
                                                 ->orderBy(DB::raw('DATE(created_at)'))
                                                 ->get();

        $listeningDays = [];
        foreach($listeningDaysQuery as $ld) {
            $listeningDays[$ld->date]          = new stdClass();
            $listeningDays[$ld->date]->date    = $ld->date;
            $listeningDays[$ld->date]->minutes = $ld->minutes;
        }
        if(count($listeningDays) > 0) {
            $date = Carbon::parse(array_values($listeningDays)[0]->date);
            while($date->isPast()) {
                $date = $date->addDays(1);
                if(!isset($listeningDays[$date->format('Y-m-d')])) {
                    $listeningDays[$date->format('Y-m-d')]          = new stdClass();
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

    public function getFavouriteYear(): string|int {
        $favouriteYearQ = SpotifyPlayActivity::where('user_id', auth()->user()->id)
                                             ->join('spotify_tracks', 'spotify_tracks.id', '=', 'spotify_play_activities.track_id')
                                             ->join('spotify_albums', 'spotify_tracks.album_id', '=', 'spotify_albums.album_id')
                                             ->where('spotify_albums.release_date', '<>', null)
                                             ->groupBy('release_year')
                                             ->select(DB::raw('YEAR(spotify_albums.release_date) as release_year'))
                                             ->orderBy(DB::raw('COUNT(*)'), 'desc')
                                             ->first();

        return $favouriteYearQ == null ? '?' : $favouriteYearQ->release_year;
    }

    public function getAverageBPM(): int {
        $bpm = SpotifyPlayActivity::where('user_id', auth()->user()->id)
                                  ->join('spotify_tracks', 'spotify_tracks.id', '=', 'spotify_play_activities.track_id')
                                  ->select(DB::raw('AVG(bpm) as bpm'))
                                  ->first()->bpm;
        return round($bpm);
    }

    public function getAverageSessionLength(): int {
        $avgSession = SpotifySession::where('user_id', auth()->user()->id)
                                    ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, timestamp_start, timestamp_end)) as sessionTime'))
                                    ->first()->sessionTime;
        return round($avgSession);
    }

    public function getTrackCount(): int {
        return SpotifyPlayActivity::where('user_id', auth()->user()->id)->groupBy('track_id')->select('track_id')->get()->count();
    }

    public function getTopArtists($timeFrom = null, $timeTo = null, int $limit = 5): Collection {
        $query = SpotifyArtist::join('spotify_track_artists', 'spotify_track_artists.artist_id', '=', 'spotify_artists.id')
                              ->join('spotify_tracks', 'spotify_tracks.id', '=', 'spotify_track_artists.track_id')
                              ->join('spotify_play_activities', 'spotify_play_activities.track_id', '=', 'spotify_tracks.id')
                              ->where('spotify_play_activities.user_id', auth()->user()->id)
                              ->groupBy('spotify_artists.id')
                              ->select('spotify_artists.*', DB::raw('SUM(duration) / 60 AS minutes'))
                              ->orderBy('minutes', 'desc')
                              ->limit($limit);
        if($timeTo != null) {
            $timeTo = Carbon::parse($timeTo);
            $query->where('spotify_play_activities.created_at', '<=', $timeTo);
        }
        if($timeFrom != null) {
            $timeFrom = Carbon::parse($timeFrom);
            $query->where('spotify_play_activities.created_at', '>=', $timeFrom);
        }

        return $query->get();
    }

    /**
     * @param null $timeFrom
     * @param null $timeTo
     *
     * @return int
     * @todo in use?
     */
    public function getPlaytime($timeFrom = null, $timeTo = null): int {
        $query = SpotifyPlayActivity::where('user_id', auth()->user()->id);

        if($timeFrom != null) {
            $timeFrom = Carbon::parse($timeFrom);
            $query->where('created_at', '>=', $timeFrom->toIso8601String());
        }
        if($timeTo != null) {
            $timeTo = Carbon::parse($timeTo);
            $query->where('created_at', '<=', $timeTo->toIso8601String());
        }

        return $query->count();
    }

    public function getTopTracks($timeFrom = null, $timeTo = null, int $limit = 3): Collection {
        $query = SpotifyPlayActivity::with(['track', 'track.album', 'track.artists'])
                                    ->where('user_id', auth()->user()->id)
                                    ->groupBy('track_id')
                                    ->select('track_id', DB::raw('SUM(duration) / 60 as minutes'))
                                    ->orderBy('minutes', 'DESC')
                                    ->limit($limit);

        if($timeTo != null) {
            $timeTo = Carbon::parse($timeTo);
            $query->where('created_at', '<=', $timeTo);
        }
        if($timeFrom != null) {
            $timeFrom = Carbon::parse($timeFrom);
            $query->where('created_at', '>=', $timeFrom);
        }

        return $query->get();
    }

    public function getLastPlayed() {
        return SpotifyPlayActivity::with(['track', 'track.album', 'track.artists'])
                                  ->where('user_id', auth()->user()->id)
                                  ->orderBy('created_at', 'desc')
                                  ->limit(1)
                                  ->first();
    }

    public function renderDailyHistory($date = null): RedirectResponse|View {
        $date = $date == null ? Carbon::today() : Carbon::parse($date);

        if($date->isAfter(Carbon::now())) {
            return redirect()->route('spotify.history')
                             ->with('alert-danger', __('general.error.future_not_possible'));
        }

        $dayQuery = SpotifyPlayActivity::where('user_id', Auth::user()->id)
                                       ->where('timestamp_start', '>=', $date->toDateString() . ' 00:00:00')
                                       ->where('timestamp_start', '<=', $date->toDateString() . ' 23:59:59');

        $history = (clone $dayQuery)->with(['track', 'device'])
                                    ->select(['timestamp_start', 'track_id', 'duration'])
                                    ->orderByDesc('timestamp_start')
                                    ->paginate(10);

        $tracksDistinct = (clone $dayQuery)->select('track_id')->groupBy('track_id')->get()->count();

        $sessions = SpotifySession::where('user_id', Auth::user()->id)
                                  ->where('timestamp_start', '>=', $date->toDateString() . ' 00:00:00')
                                  ->where('timestamp_start', '<=', $date->toDateString() . ' 23:59:59')
                                  ->count();

        return view('spotify.daily_history', [
            'date'           => $date,
            'history'        => $history,
            'minTotal'       => (clone $dayQuery)->count(),
            'tracksDistinct' => $tracksDistinct,
            'sessions'       => $sessions
        ]);
    }

    /**
     * @param int $id
     *
     * @return Renderable
     */
    public function renderArtist(int $id): Renderable {
        $artist = SpotifyArtist::findOrFail($id);
        return view('spotify.artist', [
            'artist' => $artist
        ]);
    }

    public function renderMoodMeter(): Renderable {
        $daily = auth()->user()
                       ->spotifyActivity()
                       ->with(['track'])
                       ->where('timestamp_start', '>=', Carbon::parse('-1 month')->startOfDay())
                       ->orderBy('timestamp_start', 'DESC')
                       ->get()
                       ->groupBy(function($playActivity) {
                           return $playActivity->timestamp_start->toDateString();
                       })
                       ->map(function($playActivities) {
                           return round($playActivities->avg('track.valence') * 100);
                       });

        return view('spotify.mood-o-meter.main', [
            'daily' => $daily
        ]);
    }

    public function renderExplore(): Renderable {
        $tracks       = auth()->user()->spotifyActivity()->select(['track_id'])->groupBy('track_id');
        $alreadyRated = SpotifyTrack::whereIn('id', auth()->user()->spotifyRatedTracks()->select(['track_id']))->select('track_id');

        $friends = auth()->user()->friends;

        $trackToExplore = null;

        if($friends->count() > 0 && rand(1, 100) > 40) {
            //Use a popular song from a friend
            $friend          = $friends->random(1)->first();
            $friendTopTracks = $friend->spotifyActivity()
                                      ->where('created_at', '>', Carbon::now()->subWeeks(6)->toDateString())
                                      ->groupBy('track_id')
                                      ->select([
                                                   'track_id',
                                                   DB::raw('SUM(duration) / 60 as minutes')
                                               ]);

            $trackToExplore = SpotifyTrack::joinSub($friendTopTracks, 'friends_top_tracks', function($join) {
                $join->on('spotify_tracks.track_id', '=', 'friends_top_tracks.track_id');
            })
                                          ->whereIn('spotify_tracks.track_id', $friendTopTracks->select('track_id'))
                                          ->whereNotIn('spotify_tracks.track_id', $tracks)
                                          ->whereNotIn('spotify_tracks.track_id', $alreadyRated)
                                          ->where('preview_url', '<>', null)
                                          ->orderByDesc('friends_top_tracks.minutes')
                                          ->select(['spotify_tracks.*', 'friends_top_tracks.minutes'])
                                          ->first();

        }
        if($trackToExplore !== null) {
            $trackReason = strtr(__('spotify.explore.reason.friend'), [
                ':friend' => $friend->username
            ]);
        } else {
            //Use a popular song from trends
            $trackToExplore = SpotifyTrack::whereNotIn('track_id', $tracks)
                                          ->whereNotIn('track_id', $alreadyRated)
                                          ->where('preview_url', '<>', null)
                                          ->orderByDesc('popularity')
                                          ->first();
            $trackReason    = __('spotify.explore.reason.trend');
        }


        return view('spotify.explore.index', [
            'track'         => $trackToExplore,
            'trackReason'   => $trackReason,
            'exploredToday' => auth()->user()->spotifyLikedTracks()->whereDate('created_at', Carbon::today()->toDateString())->count(),
            'exploredTotal' => auth()->user()->spotifyLikedTracks()->count(),
            'ratedTotal'    => auth()->user()->spotifyRatedTracks()->count()
        ]);
    }

    public function saveExploration(Request $request) {
        $validated = $request->validate([
                                            'track_id'      => ['required', 'exists:spotify_tracks,id'],
                                            'rating'        => ['required', 'gte:-1', 'lte:1'],
                                            'addToPlaylist' => ['required_if:rating,1', 'gte:0', 'lte:1']
                                        ]);

        SpotifyTrackRating::updateOrCreate([
                                               'user_id'  => auth()->user()->id,
                                               'track_id' => $validated['track_id'],
                                               'rating'   => $validated['rating']
                                           ]);

        if(isset($validated['addToPlaylist']) && $validated['addToPlaylist'] == '1') {
            try {
                $track = SpotifyTrack::find($validated['track_id']);

                $client = new Client();
                $result = $client->put('https://api.spotify.com/v1/me/tracks', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . auth()->user()->socialProfile->spotify_accessToken,
                        'Content-Type'  => 'application/json'
                    ],
                    'body'    => json_encode([
                                                 'ids' => [$track->track_id]
                                             ])
                ]);

                if($result->getStatusCode() == 200) {
                    return back()->with('alert-success', 'Der Track wurde in deiner Bibliothek gespeichert! :)')
                                 ->with('autoplay', '1');
                }
            } catch(GuzzleException $exception) {
                report($exception);
                return back()->with('alert-danger', 'Es ist ein Fehler aufgetreten: Wir haben keine Berechtigung, diesen Track in deine Bibliothek hinzuzufügen. ' .
                                                    'Bitte klicke <a href="/auth/redirect/spotify">hier</a> und erteile die Berechtigung.');
            }
        }

        return back()->with('autoplay', '1');
    }

}
