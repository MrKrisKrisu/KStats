<?php

namespace App\Http\Controllers;

use App\SocialLoginProfile;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;

class SocialController extends Controller {

    /**
     * Redirects to login-provider authentication
     *
     * @param $provider
     *
     * @return redirect
     */
    public function redirect($provider) {
        $sl = Socialite::driver($provider);
        if ($provider == 'spotify')
            $sl->scopes(['user-top-read', 'user-read-playback-state', 'user-read-currently-playing', 'user-modify-playback-state', 'playlist-modify-private']);
        return $sl->redirect();
    }

    /**
     *
     * @param $provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider) {
        $getInfo = Socialite::driver($provider)->user();

        $user = User::where('id', auth()->user()->id)->first();
        $socialProfile = $user->socialProfile ?: new SocialLoginProfile;
        $socialProfile->user_id = auth()->user()->id;

        if ($provider == "twitter") {
            $socialProfile->twitter_token = $getInfo->token;
            $socialProfile->twitter_tokenSecret = $getInfo->tokenSecret;
            $socialProfile->save();
        } elseif ($provider == "spotify") {
            $socialProfile->spotify_user_id = $getInfo->id;
            $socialProfile->spotify_accessToken = $getInfo->token;
            $socialProfile->spotify_refreshToken = $getInfo->refreshToken;
            $socialProfile->save();
            return redirect()->to('/spotify');
        }

        return redirect()->to('/settings');
    }

}
