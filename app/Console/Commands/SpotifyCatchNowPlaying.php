<?php

namespace App\Console\Commands;

use App\Exceptions\SpotifyTokenExpiredException;
use App\Models\SocialLoginProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use App\Http\Controllers\Backend\Spotify\FetchController;

class SpotifyCatchNowPlaying extends Command {

    protected $signature   = 'spotify:catchNowPlaying';
    protected $description = 'Catch the current playing tracks of every user and save to the database';

    public function handle(): int {
        $slProfile = SocialLoginProfile::whereNotNull('spotify_accessToken')
                                       ->where('spotify_lastRefreshed', '>', Carbon::parse('-1 hour'))
                                       ->get();

        foreach($slProfile as $profile) {
            try {
                $user = $profile->user()->first();
                FetchController::fetchRecentlyPlayed($user);
            } catch(SpotifyTokenExpiredException) {
                dump("Access Token expired from User " . $profile->user()->first()->username);
            } catch(Exception $exception) {
                dump($exception);
                report($exception);
            }
        }

        return 0;
    }

}
