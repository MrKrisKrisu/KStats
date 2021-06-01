<?php

namespace App\Http\Controllers;

use App\SpotifyPlayActivity;
use Illuminate\Contracts\Support\Renderable;
use Exception;
use App\SpotifyFriendshipPlaylist;
use App\Http\Controllers\Backend\Spotify\FriendshipPlaylistController;
use App\User;

class HomeController extends Controller {

    public function index(): Renderable {
        FriendshipPlaylistController::getFriendshipPlaylist(auth()->user(), User::find(333), true);

        $lastSpotifyTrack = SpotifyPlayActivity::with(['track', 'track.album', 'track.artists'])
                                               ->where('user_id', auth()->user()->id)
                                               ->orderBy('timestamp_start', 'DESC')
                                               ->limit(1)
                                               ->first();

        return view('home', [
            'lastSpotifyTrack' => $lastSpotifyTrack
        ]);
    }

    public static function getCurrentGitHash(): string {
        try {
            $gitBasePath = base_path() . '/.git';

            $gitStr = file_get_contents($gitBasePath . '/HEAD');
            return rtrim(preg_replace("/(.*?\/){2}/", '', $gitStr));
        } catch(Exception $e) {
            report($e);
            return 'unknown';
        }
    }
}
