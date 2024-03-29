<?php

namespace App\Console\Commands;

use App\Http\Controllers\SpotifyAPIController;
use App\Models\SocialLoginProfile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;

class SpotifyTokenRefresh extends Command {

    protected $signature   = 'spotify:tokenRefresh';
    protected $description = 'Get new AccessTokens to authorized Spotify Profiles';

    public function handle(): int {
        $slProfile = SocialLoginProfile::with(['user'])
                                       ->whereNotNull('spotify_accessToken')
                                       ->where(function($query) {
                                           $query->where(function($query) {
                                               $query->where('spotify_lastRefreshed', '<', Carbon::now()->subMinutes(30)->toIso8601String());
                                               $query->where('spotify_lastRefreshed', '>', Carbon::now()->subDay()->toIso8601String());
                                           });
                                           $query->orWhere('spotify_lastRefreshed', null);
                                       })
                                       ->get();

        foreach($slProfile as $profile) {
            try {
                $refreshedToken = SpotifyAPIController::getNewAccessToken($profile->spotify_refreshToken);

                if(!$refreshedToken) {
                    Log::error("[Spotify] [RefreshToken] Error while refreshing Token from User User " . $profile->user->id . " / " . $profile->user->username . ". No Token is returned.");
                    continue;
                }

                $profile->update([
                                     'spotify_accessToken'   => $refreshedToken->access_token,
                                     'spotify_lastRefreshed' => Carbon::now()
                                 ]);

            } catch(Exception $e) {
                report($e);
                dump($e->getMessage());
            }
        }

        return 0;
    }

}
