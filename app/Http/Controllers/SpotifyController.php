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

        $uniqueSongs = SpotifyPlayActivity::where('user_id', auth()->user()->id)->groupBy('track_id')->select('track_id')->get()->count();
        $favouriteYear_q = SpotifyPlayActivity::where('user_id', auth()->user()->id)
            ->join('spotify_tracks', 'spotify_tracks.track_id', '=', 'spotify_play_activities.track_id')
            ->join('spotify_albums', 'spotify_tracks.album_id', '=', 'spotify_albums.album_id')
            ->where('spotify_albums.release_date', '<>', null)
            ->groupBy('release_year')
            ->select(DB::raw('YEAR(spotify_albums.release_date) as release_year'))
            ->orderBy(DB::raw('COUNT(*)'), 'desc')
            ->first();

        $favouriteYear = $favouriteYear_q == NULL ? '?' : $favouriteYear_q->release_year;

        $bpm = SpotifyPlayActivity::where('user_id', auth()->user()->id)
            ->join('spotify_tracks', 'spotify_tracks.track_id', '=', 'spotify_play_activities.track_id')
            ->select(DB::raw('AVG(bpm) as bpm'))
            ->first()->bpm;

        $avgSession = SpotifySession::where('user_id', auth()->user()->id)
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, timestamp_start, timestamp_end)) as sessionTime'))
            ->first()->sessionTime;

        $hearedMinutesTotal = SpotifyPlayActivity::where('user_id', auth()->user()->id)->count();
        $hearedMinutes30d = SpotifyPlayActivity::where('user_id', auth()->user()->id)->where('created_at', '>', DB::raw('(NOW() - INTERVAL 30 DAY)'))->count();
        $hearedMinutes7d = SpotifyPlayActivity::where('user_id', auth()->user()->id)->where('created_at', '>', DB::raw('(NOW() - INTERVAL 7 DAY)'))->count();
        $hearedMinutes1d = SpotifyPlayActivity::where('user_id', auth()->user()->id)->where('created_at', '>', DB::raw('(NOW() - INTERVAL 1 DAY)'))->count();

        $lastPlayActivity = SpotifyPlayActivity::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        $topTracksTotal = SpotifyPlayActivity::where('user_id', auth()->user()->id)
            ->groupBy('track_id')
            ->select('track_id', DB::raw('COUNT(*) as minutes'))
            ->orderBy('minutes', 'DESC')
            ->limit(3)
            ->get();

        $topTracks30d = SpotifyPlayActivity::where('user_id', auth()->user()->id)
            ->where('created_at', '>', DB::raw('(NOW() - INTERVAL 30 DAY)'))
            ->groupBy('track_id')
            ->select('track_id', DB::raw('COUNT(*) as minutes'))
            ->orderBy('minutes', 'DESC')
            ->limit(3)
            ->get();

        $topArtistsTotal = SpotifyArtist::join('spotify_track_artists', 'spotify_track_artists.artist_id', '=', 'spotify_artists.id')
            ->join('spotify_tracks', 'spotify_tracks.id', '=', 'spotify_track_artists.track_id')
            ->join('spotify_play_activities', 'spotify_play_activities.track_id', '=', 'spotify_tracks.track_id')
            ->where('spotify_play_activities.user_id', auth()->user()->id)
            ->groupBy('spotify_artists.id')
            ->select('spotify_artists.*', DB::raw('COUNT(*) AS minutes'))
            ->orderBy('minutes', 'desc')
            ->limit(5)
            ->get();

        $topArtists30d = SpotifyArtist::join('spotify_track_artists', 'spotify_track_artists.artist_id', '=', 'spotify_artists.id')
            ->join('spotify_tracks', 'spotify_tracks.id', '=', 'spotify_track_artists.track_id')
            ->join('spotify_play_activities', 'spotify_play_activities.track_id', '=', 'spotify_tracks.track_id')
            ->where('spotify_play_activities.user_id', auth()->user()->id)
            ->where('spotify_play_activities.created_at', '>', DB::raw('(NOW() - INTERVAL 30 DAY)'))
            ->groupBy('spotify_artists.id')
            ->select('spotify_artists.*', DB::raw('COUNT(*) AS minutes'))
            ->orderBy('minutes', 'desc')
            ->limit(5)
            ->get();

        $topArtists7d = SpotifyArtist::join('spotify_track_artists', 'spotify_track_artists.artist_id', '=', 'spotify_artists.id')
            ->join('spotify_tracks', 'spotify_tracks.id', '=', 'spotify_track_artists.track_id')
            ->join('spotify_play_activities', 'spotify_play_activities.track_id', '=', 'spotify_tracks.track_id')
            ->where('spotify_play_activities.user_id', auth()->user()->id)
            ->where('spotify_play_activities.created_at', '>', DB::raw('(NOW() - INTERVAL 7 DAY)'))
            ->groupBy('spotify_artists.id')
            ->select('spotify_artists.*', DB::raw('COUNT(*) AS minutes'))
            ->orderBy('minutes', 'desc')
            ->limit(5)
            ->get();

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
            'hearedMinutesTotal' => $hearedMinutesTotal,
            'hearedMinutes30d' => $hearedMinutes30d,
            'hearedMinutes7d' => $hearedMinutes7d,
            'hearedMinutes1d' => $hearedMinutes1d,
            'uniqueSongs' => $uniqueSongs,
            'bpm' => round($bpm),
            'avgSession' => round($avgSession),
            'lastPlayActivity' => $lastPlayActivity,
            'topTracksTotal' => $topTracksTotal,
            'topTracks30d' => $topTracks30d,
            'topArtistsTotal' => $topArtistsTotal,
            'topArtists30d' => $topArtists30d,
            'topArtists7d' => $topArtists7d,
            'favouriteYear' => $favouriteYear,
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
        UserSettings::set(auth()->user()->id, 'spotify_createOldPlaylist', $request->spotify_createOldPlaylist == 'on');
        UserSettings::set(auth()->user()->id, 'spotify_oldPlaylist_minutesTop', (int)$request->spotify_oldPlaylist_minutesTop);
        UserSettings::set(auth()->user()->id, 'spotify_oldPlaylist_days', (int)$request->spotify_oldPlaylist_days);
        UserSettings::set(auth()->user()->id, 'spotify_oldPlaylist_songlimit', (int)$request->spotify_oldPlaylist_songlimit);

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

        return SpotifyTrack::whereIn('track_id', $trackList)->orderBy('popularity', 'desc')->get();

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
            0 => 'Montag',
            1 => 'Dienstag',
            2 => 'Mittwoch',
            3 => 'Donnerstag',
            4 => 'Freitag',
            5 => 'Samstag',
            6 => 'Sonntag'
        ];
        return $a[$wd] ?? $wd;
    }

}
