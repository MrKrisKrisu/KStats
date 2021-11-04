<?php

namespace App\Http\Controllers;

use App\Models\SocialLoginProfile;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller {

    /**
     * Redirects to login-provider authentication
     *
     * @param $provider
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($provider): \Symfony\Component\HttpFoundation\RedirectResponse {
        $driver = Socialite::driver($provider);
        if($provider == 'spotify') {
            $driver->scopes([
                                'user-read-recently-played', 'user-top-read', 'user-read-playback-state',
                                'user-read-currently-playing', 'user-modify-playback-state', 'playlist-modify-private',
                                'user-library-modify'
                            ]);
        }
        return $driver->redirect();
    }

    /**
     * @param $provider
     *
     * @return RedirectResponse
     */
    public function callback($provider): RedirectResponse {
        $getInfo = Socialite::driver($provider)->user();

        //User is not logged in, try to log in with social profile
        if(!Auth::check()) {
            if($provider !== "spotify") {
                return redirect('login')->with('status', $provider . ' is not supported');
            }

            $socialProfile = SocialLoginProfile::where('spotify_user_id', $getInfo->id)->first();
            if($socialProfile == null) {
                //TODO: Registration with Spotify
                return redirect('login')->with('status', 'You are not connected to an KStats Account. Please register first and connect your Spotify Account.');
            }
            $socialProfile->update([
                                       'spotify_accessToken'  => $getInfo->token,
                                       'spotify_refreshToken' => $getInfo->refreshToken,
                                       'spotify_expires_at'   => Carbon::now()->addSeconds($getInfo->expiresIn)->toIso8601String(),
                                       'spotify_scopes'       => $getInfo->accessTokenResponseBody['scope'],
                                       'last_login'           => Carbon::now()->toIso8601String(),
                                   ]);

            Auth::login($socialProfile->user()->first()); //wtf?
        }

        $socialProfile = SocialLoginProfile::firstOrCreate(['user_id' => auth()->user()->id]);

        if($provider == "twitter") {
            $socialProfile->update([
                                       'twitter_token'       => $getInfo->token,
                                       'twitter_tokenSecret' => $getInfo->tokenSecret
                                   ]);

            TwitterController::verifyProfile($socialProfile);
        } elseif($provider == "spotify") {
            $socialProfile->update([
                                       'spotify_user_id'       => $getInfo->id,
                                       'spotify_accessToken'   => $getInfo->token,
                                       'spotify_refreshToken'  => $getInfo->refreshToken,
                                       'spotify_lastRefreshed' => Carbon::now()->toIso8601String(),
                                       'spotify_expires_at'    => Carbon::now()->addSeconds($getInfo->expiresIn)->toIso8601String(),
                                       'spotify_scopes'        => $getInfo->accessTokenResponseBody['scope'],
                                       'last_login'            => Carbon::now()->toIso8601String(),
                                   ]);
            return redirect()->to('/spotify');
        }

        return redirect()->to('/settings');
    }

}
