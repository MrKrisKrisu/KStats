<?php

namespace App\Http\Controllers;

use App\Exceptions\SpotifyTokenExpiredException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpotifyAPIController extends Controller
{

    /**
     * @param $accessToken
     * @return bool|mixed
     * @throws SpotifyTokenExpiredException
     */
    public static function getNowPlaying($accessToken)
    {
        $client = new Client(['http_errors' => false]);
        $result = $client->get('https://api.spotify.com/v1/me/player/currently-playing', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);

        if ($result->getStatusCode() == 401)
            throw new SpotifyTokenExpiredException();

        if ($result->getStatusCode() == 204) {
            Log::debug("User is not listening to something or is in private session.");
            return false;
        }

        if ($result->getStatusCode() != 200) {
            Log::debug("Error while trying to retrieve currently-playing from Spotify API.");
            Log::debug($result->getBody());
            return false;
        }

        $data = json_decode($result->getBody()->getContents());

        if (isset($data->error))
            return false;
        if (!$data->is_playing)
            return false;

        return $data;
    }

    /**
     * @param $accessToken
     * @return bool|mixed
     */
    public static function getDevices($accessToken)
    {
        $client = new Client(['http_errors' => false]);
        $result = $client->get('https://api.spotify.com/v1/me/player/devices', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);

        if ($result->getStatusCode() != 200) {
            Log::info("Error while trying to retrieve from Spotify API.");
            return false;
        }

        $data = json_decode($result->getBody()->getContents());

        if (isset($data->error))
            return false;

        return $data;
    }

    public static function getNewAccessToken($refreshToken)
    {
        $client = new Client(['http_errors' => false]);
        $result = $client->post('https://accounts.spotify.com/api/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken
            ],
            'headers' => [
                'authorization' => 'Basic ' . base64_encode(env('SPOTIFY_CLIENT_ID') . ':' . env('SPOTIFY_CLIENT_SECRET'))
            ]
        ]);

        if ($result->getStatusCode() != 200)
            return false;

        $data = json_decode($result->getBody()->getContents());

        if (isset($data->error))
            return false;

        return $data;
    }

    public static function getAudioFeatures(array $ids)
    {
        $client = new Client();
        $result = $client->get('https://api.spotify.com/v1/audio-features?ids=' . implode(',', $ids), [
            'headers' => [
                'Authorization' => 'Bearer ' . DB::table('social_login_profiles')->where('spotify_lastRefreshed', '>', DB::raw('NOW() - INTERVAL 50 MINUTE'))->first()->spotify_accessToken
            ]
        ]);

        if ($result->getStatusCode() != 200) {
            Log::error('Error while retrieving audio-features from Spotify API.');
            return false;
        }

        $data = json_decode($result->getBody()->getContents());

        if (isset($data->error))
            return false;

        return $data;
    }

    /**
     * @param String $accessToken
     * @param String $time_span short_term, medium_term or long_term
     * @return bool|mixed
     * @throws SpotifyTokenExpiredException
     */
    public static function getTopTracks(string $accessToken, string $time_span)
    {
        $client = new Client(['http_errors' => false]);
        $result = $client->get('https://api.spotify.com/v1/me/top/tracks/?limit=50&time_range=' . $time_span, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);

        if ($result->getStatusCode() == 401)
            throw new SpotifyTokenExpiredException();

        if ($result->getStatusCode() != 200) {
            Log::error('Error while retrieving audio-features from Spotify API.');
            return false;
        }

        $data = json_decode($result->getBody()->getContents());

        if (isset($data->error))
            return false;

        return $data;
    }

}
