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
     * @return RedirectResponse
     */
    public function redirect($provider): RedirectResponse {
        $driver = Socialite::driver($provider);
        if($provider == 'spotify')
            $driver->scopes(['user-top-read', 'user-read-playback-state', 'user-read-currently-playing', 'user-modify-playback-state', 'playlist-modify-private', 'user-library-modify']);
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
            if($provider !== "spotify")
                return redirect('login')->with('status', $provider . ' is not supported');

            $socialProfile = SocialLoginProfile::where('spotify_user_id', $getInfo->id)->first();
            if($socialProfile == null)
                return redirect('login')->with('status', 'You are not connected to an KStats Account. Please register first and connect your Spotify Account.');

            $socialProfile->update([
                                       'spotify_accessToken'  => $getInfo->token,
                                       'spotify_refreshToken' => $getInfo->refreshToken
                                   ]);

            $socialProfile->user->update([
                                             'last_login' => Carbon::now()
                                         ]);

            Auth::login($socialProfile->user);
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
                                       'spotify_lastRefreshed' => Carbon::now()
                                   ]);
            return redirect()->to('/spotify');
        }

        return redirect()->to('/settings');
    }

}
