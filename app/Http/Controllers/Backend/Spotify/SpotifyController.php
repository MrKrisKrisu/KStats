<?php

namespace App\Http\Controllers\Backend\Spotify;

use App\Http\Controllers\Controller;
use App\Models\User;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;
use App\Exceptions\SpotifyTokenExpiredException;
use App\Models\SocialLoginProfile;
use Carbon\Carbon;
use Exception;

abstract class SpotifyController extends Controller {

    /**
     * @param User $user
     *
     * @return SpotifyWebAPI
     * @throws SpotifyTokenExpiredException
     */
    public static function getApi(User $user): SpotifyWebAPI {
        if($user?->socialProfile?->spotify_accessToken == null) {
            throw new SpotifyTokenExpiredException();
        }

        $session = new Session(
            clientId: config('services.spotify.client_id'),
            clientSecret: config('services.spotify.client_secret'),
        );
        $session->setAccessToken($user->socialProfile->spotify_accessToken);

        return new SpotifyWebAPI([], $session);
    }

    public static function getGeneralApi(): SpotifyWebAPI {
        $session = new Session(
            clientId: config('services.spotify.client_id'),
            clientSecret: config('services.spotify.client_secret'),
        );

        return new SpotifyWebAPI([], $session);
    }

    /**
     * @return SpotifyWebAPI
     * @throws Exception
     */
    public static function getRandomApi(): SpotifyWebAPI {
        $user = SocialLoginProfile::whereNotNull('spotify_accessToken')
                                  ->where('spotify_lastRefreshed', '>=', Carbon::now()->subMinutes(45)->toIso8601String())
                                  ->inRandomOrder()
                                  ->limit(1)?->first()?->user;

        if($user == null) {
            throw new Exception('No active user found');
        }

        return self::getApi($user);
    }

}
