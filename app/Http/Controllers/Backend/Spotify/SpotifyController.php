<?php

namespace App\Http\Controllers\Backend\Spotify;

use App\Http\Controllers\Controller;
use App\Models\User;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;
use App\Exceptions\SpotifyTokenExpiredException;

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

}
