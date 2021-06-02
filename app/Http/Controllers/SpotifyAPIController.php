<?php

namespace App\Http\Controllers;

use App\Exceptions\SpotifyAPIException;
use App\Exceptions\SpotifyTokenExpiredException;
use App\Models\SocialLoginProfile;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class SpotifyAPIController extends Controller {

    /**
     * @param $accessToken
     *
     * @return bool|mixed
     * @throws SpotifyTokenExpiredException
     */
    public static function getNowPlaying($accessToken) {
        $client = new Client(['http_errors' => false]);
        $result = $client->get('https://api.spotify.com/v1/me/player/currently-playing', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);

        if($result->getStatusCode() == 401)
            throw new SpotifyTokenExpiredException();

        //User is not listening to something or is in private session.
        if($result->getStatusCode() == 204)
            return false;

        if($result->getStatusCode() != 200) {
            Log::error("Error while trying to retrieve currently-playing from Spotify API. StatusCode: " . $result->getStatusCode());
            Log::error($result->getBody());
            return false;
        }

        $data = json_decode($result->getBody()->getContents());

        if(isset($data->error))
            return false;
        if(!$data->is_playing)
            return false;

        return $data;
    }

    /**
     * @param $accessToken
     *
     * @return bool|mixed
     */
    public static function getDevices($accessToken) {
        $client = new Client(['http_errors' => false]);
        $result = $client->get('https://api.spotify.com/v1/me/player/devices', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);

        if($result->getStatusCode() != 200) {
            Log::error("Error while trying to retrieve device from Spotify API. StatusCode: " . $result->getStatusCode());
            Log::error($result->getBody());
            return false;
        }

        $data = json_decode($result->getBody()->getContents());

        if(isset($data->error))
            return false;

        return $data;
    }

    public static function getNewAccessToken($refreshToken) {
        $client = new Client(['http_errors' => false]);
        $result = $client->post('https://accounts.spotify.com/api/token', [
            'form_params' => [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refreshToken
            ],
            'headers'     => [
                'authorization' => 'Basic ' . base64_encode(config('services.spotify.client_id') . ':' . config('services.spotify.client_secret'))
            ]
        ]);

        if($result->getStatusCode() != 200)
            return false;

        $data = json_decode($result->getBody()->getContents());

        if(isset($data->error)) {
            Log::debug($data);
            return false;
        }

        return $data;
    }

    /**
     * @param string $implodedIDs
     *
     * @return bool|mixed
     * @throws SpotifyAPIException|GuzzleException
     */
    public static function getAudioFeatures(string $implodedIDs) {
        $rdmSocialProfile = SocialLoginProfile::where('spotify_lastRefreshed', '>', Carbon::now()->subMinutes(50)->toDateString())
                                              ->where('spotify_accessToken', '<>', null)
                                              ->first();

        if($rdmSocialProfile === null) {
            throw new SpotifyAPIException('Cannot find any (random) Spotify Key to make this request.');
        }

        $client = new Client();
        $result = $client->get('https://api.spotify.com/v1/audio-features?ids=' . $implodedIDs, [
            'headers' => [
                'Authorization' => 'Bearer ' . $rdmSocialProfile->spotify_accessToken
            ]
        ]);

        if($result->getStatusCode() != 200) {
            Log::error("Error while trying to retrieve audio-features from Spotify API. StatusCode: " . $result->getStatusCode());
            Log::error($result->getBody());
            throw new SpotifyAPIException();
        }

        $data = json_decode($result->getBody()->getContents());

        if(isset($data->error))
            throw new SpotifyAPIException();

        return $data;
    }

    /**
     * @param String $accessToken
     * @param String $time_span short_term, medium_term or long_term
     *
     * @return bool|mixed
     * @throws SpotifyTokenExpiredException
     * @throws SpotifyAPIException
     */
    public static function getTopTracks(string $accessToken, string $time_span) {
        $client = new Client(['http_errors' => false]);
        $result = $client->get('https://api.spotify.com/v1/me/top/tracks/?limit=50&time_range=' . $time_span, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);

        if($result->getStatusCode() == 401)
            throw new SpotifyTokenExpiredException();

        if($result->getStatusCode() != 200) {
            Log::error("Error while trying to retrieve top-tracks from Spotify API. StatusCode: " . $result->getStatusCode());
            Log::error($result->getBody());
            throw new SpotifyAPIException();
        }

        $data = json_decode($result->getBody()->getContents());

        if(isset($data->error))
            throw new SpotifyAPIException();

        return $data;
    }

}
