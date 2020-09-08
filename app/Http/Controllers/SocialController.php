<?php

namespace App\Http\Controllers;

use App\SocialLoginProfile;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;

class SocialController extends Controller
{

    /**
     * Redirects to login-provider authentication
     *
     * @param $provider
     *
     * @return redirect
     */
    public function redirect($provider)
    {
        $driver = Socialite::driver($provider);
        if ($provider == 'spotify')
            $driver->scopes(['user-top-read', 'user-read-playback-state', 'user-read-currently-playing', 'user-modify-playback-state', 'playlist-modify-private']);
        return $driver->redirect();
    }

    /**
     * @param $provider
     *
     * @return RedirectResponse
     */
    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->user();

        //User is not logged in, try to log in with social profile
        if (!Auth::check()) {
            if ($provider !== "spotify")
                return redirect('login')->with('status', $provider . ' is not supported');

            $slp = SocialLoginProfile::where('spotify_user_id', $getInfo->id)->first();
            if ($slp == NULL)
                return redirect('login')->with('status', 'You are not connected to an KStats Account. Please register first and connect your Spotify Account.');

            $slp->spotify_accessToken = $getInfo->token;
            $slp->spotify_refreshToken = $getInfo->refreshToken;
            $slp->update();

            Auth::login($slp->user);
        }

        $user = User::find(Auth::user()->id);
        $socialProfile = $user->socialProfile ?: new SocialLoginProfile;
        $socialProfile->user_id = auth()->user()->id;

        if ($provider == "twitter") {
            $socialProfile->twitter_token = $getInfo->token;
            $socialProfile->twitter_tokenSecret = $getInfo->tokenSecret;
            $socialProfile->save();

            TwitterController::verifyProfile($socialProfile);
        } elseif ($provider == "spotify") {
            $socialProfile->spotify_user_id = $getInfo->id;
            $socialProfile->spotify_accessToken = $getInfo->token;
            $socialProfile->spotify_refreshToken = $getInfo->refreshToken;
            $socialProfile->spotify_lastRefreshed = Carbon::now();
            $socialProfile->save();
            return redirect()->to('/spotify');
        }

        return redirect()->to('/settings');
    }

}
