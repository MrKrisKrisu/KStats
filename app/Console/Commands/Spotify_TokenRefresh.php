<?php

namespace App\Console\Commands;

use App\Http\Controllers\SpotifyAPIController;
use App\SocialLoginProfile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Spotify_TokenRefresh extends Command {

    protected $signature   = 'spotify:tokenRefresh';
    protected $description = 'Get new AccessTokens to authorized Spotify Profiles';

    public function __construct() {
        parent::__construct();
    }

    public function handle(): int {
        $slProfile = SocialLoginProfile::with(['user'])
                                       ->whereNotNull('spotify_accessToken')
                                       ->where(function($query) {
                                           $query->where('spotify_lastRefreshed', '<', Carbon::now()->subMinutes(30));
                                           $query->where('spotify_lastRefreshed', '>', Carbon::now()->subDays(1));
                                       })
                                       ->orWhere('spotify_lastRefreshed', null)
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

            } catch(\Exception $e) {
                report($e);
                dump($e->getMessage());
            }
        }

        return 0;
    }

}
