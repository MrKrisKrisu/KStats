<?php

namespace App\Console\Commands;

use App\Http\Controllers\SpotifyController;
use App\Models\UserSettings;
use Illuminate\Console\Command;
use Exception;
use App\Models\SpotifyFriendshipPlaylist;
use Carbon\Carbon;
use App\Http\Controllers\Backend\Spotify\FriendshipPlaylistController;

class SpotifyPlaylistRefresh extends Command {

    protected $signature = 'spotify:playlistRefresh';

    public function handle(): int {
        $this->regenerateLostTracksPlaylists();
        $this->regenerateFriendshipPlaylists();
        return 0;
    }

    private function regenerateFriendshipPlaylists() {
        $playlists = SpotifyFriendshipPlaylist::where('last_refreshed', '<', Carbon::now()->subDay()->toIso8601String())->get();

        foreach($playlists as $playlist) {
            try {
                echo '* Regenerate FriendshipPlaylist from ' . $playlist->user->id . ' and ' . $playlist->friend->id . PHP_EOL;
                FriendshipPlaylistController::refreshFriendshipPlaylist($playlist->user, $playlist->friend);
                echo '** Refresh successful.' . PHP_EOL;
            } catch(Exception $exception) {
                echo '** Error while refreshing!' . PHP_EOL;
                echo '** ' . $exception->getMessage() . PHP_EOL;
            }
        }
    }

    private function regenerateLostTracksPlaylists(): void {
        $lpUsers = UserSettings::with(['user'])
                               ->where('name', 'spotify_createOldPlaylist')
                               ->where('val', '1')
                               ->get()
                               ->map(function($setting) {
                                   return $setting->user;
                               });

        foreach($lpUsers as $user) {
            try {
                echo "* Regenerate Lost Tracks Playlist from user " . $user->id . PHP_EOL;

                SpotifyController::generateLostPlaylist($user);
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        }
    }

}
