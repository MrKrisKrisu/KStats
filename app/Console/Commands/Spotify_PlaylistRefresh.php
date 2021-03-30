<?php

namespace App\Console\Commands;

use App\Http\Controllers\SpotifyController;
use App\User;
use App\UserSettings;
use Illuminate\Console\Command;
use Exception;

class Spotify_PlaylistRefresh extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spotify:playlistRefresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command
     */
    public function handle() {
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
