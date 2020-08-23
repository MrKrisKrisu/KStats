<?php

namespace App\Console\Commands;

use App\Http\Controllers\SpotifyAPIController;
use App\SocialLoginProfile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Spotify_TokenRefresh extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spotify:tokenRefresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get new AccessTokens to authorized Spotify Profiles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command
     */
    public function handle()
    {
        $slProfile = SocialLoginProfile::whereNotNull('spotify_accessToken')
            ->where(function ($query) {
                $query->where('spotify_lastRefreshed', '<', \Carbon\Carbon::now()->subMinutes(30));
                $query->where('spotify_lastRefreshed', '>', \Carbon\Carbon::now()->subDays(1));
            })
            ->orWhere('spotify_lastRefreshed', null)
            ->get();

        foreach ($slProfile as $profile) {
            try {
                $user = $profile->user()->first();
                Log::info("[Spotify] [RefreshToken] Checking User " . $user->id);

                $refreshedToken = SpotifyAPIController::getNewAccessToken($profile->spotify_refreshToken);

                if (!$refreshedToken) {
                    Log::info("[Spotify] [RefreshToken] Error while refreshing Token from User User " . $user->id . ". No Token is returned.");
                    continue;
                }

                $profile->spotify_accessToken = $refreshedToken->access_token;
                $profile->spotify_lastRefreshed = \Carbon\Carbon::now();
                $profile->save();

                Log::info("[Spotify] [RefreshToken] Successfully refreshed AccessToken from User " . $user->id);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
        }

        return 0;
    }

}
