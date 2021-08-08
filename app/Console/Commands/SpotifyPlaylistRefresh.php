<?php

namespace App\Console\Commands;

use App\Http\Controllers\SpotifyController;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Console\Command;
use Exception;

class SpotifyPlaylistRefresh extends Command {

    protected $signature = 'spotify:playlistRefresh';

    public function handle(): int {
        $users = UserSettings::where('name', 'spotify_createOldPlaylist')->where('val', '1')->select('user_id')->get();
        foreach($users as $user) {
            try {
                $user = User::find($user->user_id);

                SpotifyController::generateLostPlaylist($user);
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        }

        return 0;
    }

}
