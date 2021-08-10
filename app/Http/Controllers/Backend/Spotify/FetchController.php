<?php

namespace App\Http\Controllers\Backend\Spotify;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\SpotifyAPIController;
use Illuminate\Support\Facades\Log;
use App\Models\SpotifyContext;
use App\Models\SpotifyDevice;
use App\Models\SpotifyAlbum;
use App\Models\SpotifyTrack;
use App\Models\SpotifyPlayActivity;
use App\Models\SpotifyArtist;
use App\Models\SpotifySession;
use Illuminate\Support\Facades\DB;
use stdClass;
use SpotifyWebAPI\SpotifyWebAPIException;
use Exception;
use App\Exceptions\SpotifyTokenExpiredException;

abstract class FetchController extends Controller {

    public static function fetchRecentlyPlayed(User $user) {
        try {
            if(!str_contains($user->socialProfile->spotify_scopes, 'user-read-recently-played')) {
                throw new SpotifyWebAPIException('Insufficient client scope');
            }

            if(Carbon::now()->minute % 5 != 0) {
                //Temporary reduce api requests if user have permission to read last tracks.
                return;
            }

            echo strtr('* Fetched recently played for User :userId.', [':userId' => $user->id]) . PHP_EOL;

            $spotifyApi = SpotifyController::getApi($user);
            $data       = $spotifyApi->getMyRecentTracks();

            foreach($data->items as $recentlyPlayed) {
                echo '* Fetched item.' . PHP_EOL;

                $rawTrack = $recentlyPlayed->track;
                $rawAlbum = $rawTrack->album;

                $playedAt = Carbon::parse($recentlyPlayed->played_at, '+00:00')->setTimezone('Europe/Berlin');

                $album   = self::parseAlbum($rawAlbum);
                $track   = self::parseTrack($rawTrack);
                $context = isset($recentlyPlayed->context->uri) ? SpotifyContext::firstOrCreate(['uri' => $recentlyPlayed->context->uri]) : null;

                SpotifyPlayActivity::updateOrCreate([
                                                        'user_id'         => $user->id,
                                                        'track_id'        => $track->id,
                                                        'timestamp_start' => $playedAt->toDateTimeString(),
                                                    ], [
                                                        'duration'    => $rawTrack->duration_ms / 1000,
                                                        'progress_ms' => $rawTrack->duration_ms,
                                                        'context_id'  => $context?->id,
                                                        //'device_id'   => $activeDevice?->id
                                                    ]);

                self::saveArtists($rawTrack, $album, $track);
                self::updateSession($user, $playedAt);

                echo '_________________________________' . PHP_EOL;
            }
        } catch(SpotifyWebAPIException $exception) {
            if($exception->getMessage() == 'Insufficient client scope') {
                echo '***********************+ Insufficient client scope' . PHP_EOL;
                echo '***********************+ trying legacy script' . PHP_EOL;
                self::legacyFetch($user);
            }
        } catch(SpotifyTokenExpiredException) {
            echo "Access Token expired from User " . $user->username . PHP_EOL;
        } catch(Exception $exception) {
            report($exception);
        }
    }

    private static function parseAlbum(stdClass $rawAlbum): SpotifyAlbum {
        $album_release_date = $rawAlbum->release_date;
        if($rawAlbum->release_date_precision == 'month') {
            $album_release_date .= "-01";
        } elseif($rawAlbum->release_date_precision == 'year') {
            $album_release_date .= "-01-01";
        }

        $album = SpotifyAlbum::updateOrCreate(
            [
                'album_id' => $rawAlbum->id
            ], [
                'name'         => $rawAlbum->name,
                'imageUrl'     => $rawAlbum->images[0]->url,
                'release_date' => $album_release_date
            ]
        );
        echo strtr('** Parsed album :albumName', [':albumName' => $album->name]) . PHP_EOL;
        return $album;
    }

    private static function parseTrack(stdClass $rawTrack): SpotifyTrack {
        $track = SpotifyTrack::updateOrCreate(
            [
                'track_id' => $rawTrack->id
            ], [
                'name'        => $rawTrack->name,
                'album_id'    => $rawTrack->album->id,
                'preview_url' => $rawTrack->preview_url,
                'popularity'  => $rawTrack->popularity,
                'explicit'    => $rawTrack->explicit,
                'duration_ms' => $rawTrack->duration_ms
            ]
        );
        echo strtr('** Parsed track :trackName', [':trackName' => $track->name]) . PHP_EOL;
        return $track;
    }

    private static function saveArtists(stdClass $rawTrack, SpotifyAlbum $album, SpotifyTrack $track) {
        foreach($rawTrack->album->artists as $artist) {
            $artist = SpotifyArtist::updateOrCreate(
                [
                    'artist_id' => $artist->id
                ], [
                    'name' => $artist->name
                ]
            );
            $album->artists()->syncWithoutDetaching($artist);
        }
        foreach($rawTrack->artists as $artist) {
            $artist = SpotifyArtist::updateOrCreate(
                [
                    'artist_id' => $artist->id
                ], [
                    'name' => $artist->name
                ]
            );

            $artist->tracks()->syncWithoutDetaching($track);
        }
    }

    private static function updateSession(User $user, Carbon $since) {
        $session = SpotifySession::where('user_id', $user->id)
                                 ->where('timestamp_end', '>', DB::raw('(NOW() - INTERVAL 5 MINUTE)'))
                                 ->first();

        if($session == null) {
            SpotifySession::create([
                                       'user_id'         => $user->id,
                                       'timestamp_start' => $since->toIso8601String(),
                                       'timestamp_end'   => Carbon::now()->toIso8601String()
                                   ]);
        } else {
            $session->update([
                                 'timestamp_end' => Carbon::now()->toIso8601String()
                             ]);
        }
    }

    /****/

    private static function legacyFetch(User $user) {
        echo ("[Spotify] [CatchNowPlaying] Checking User " . $user->id . ' / ' . $user->username) . PHP_EOL;
        $profile    = $user->socialProfile;
        $nowPlaying = SpotifyAPIController::getNowPlaying($profile->spotify_accessToken);

        if(!$nowPlaying) {
            return;
        }

        //TODO: Local tracks are currently not supported.
        if(isset($nowPlaying->item->uri) && str_contains($nowPlaying->item->uri, 'spotify:local:')) {
            echo '* Local tracks are currently unsupported.' . PHP_EOL;
            return;
        }

        //TODO: Support episodes
        if(isset($nowPlaying->currently_playing_type) && $nowPlaying->currently_playing_type == 'episode') {
            echo '* Episodes are currently unsupported.' . PHP_EOL;
            return;
        }

        if(isset($nowPlaying->currently_playing_type) && $nowPlaying->currently_playing_type == 'ad') {
            echo '* Ads are unsupported.' . PHP_EOL;
            return;
        }

        if(!isset($nowPlaying->item->id)) {
            Log::debug('Error: nowPlaying->item->id not found.');
            Log::debug(print_r($nowPlaying, true));
            return;
        }

        $timestamp_start = date('Y-m-d H:i:s', $nowPlaying->timestamp / 1000);
        $progress_ms     = (int)$nowPlaying->progress_ms;
        $context         = isset($nowPlaying->context->uri) ? SpotifyContext::firstOrCreate(['uri' => $nowPlaying->context->uri]) : null;

        $devices      = SpotifyAPIController::getDevices($profile->spotify_accessToken);
        $activeDevice = null;

        foreach($devices->devices as $device) {
            $de = SpotifyDevice::updateOrCreate([
                                                    'device_id' => $device->id
                                                ], [
                                                    'user_id' => $user->id,
                                                    'name'    => $device->name,
                                                    'type'    => $device->type
                                                ]);
            if($device->is_active)
                $activeDevice = $de;
        }

        $album = self::parseAlbum($nowPlaying->item->album);
        $track = self::parseTrack($nowPlaying->item);

        $duration = $progress_ms / 1000;
        if($duration + 60 > $track->duration_ms / 1000) {
            //if duration + 60 greater than the length of the track set to maximum,
            //because the song will probably have run through by the next script call
            $duration = $track->duration_ms / 1000;
        }

        SpotifyPlayActivity::updateOrCreate([
                                                'user_id'         => $user->id,
                                                'track_id'        => $track->id,
                                                'timestamp_start' => $timestamp_start,
                                            ], [
                                                'duration'    => $duration,
                                                'progress_ms' => $progress_ms,
                                                'context_id'  => $context?->id,
                                                'device_id'   => $activeDevice?->id
                                            ]);

        self::saveArtists($nowPlaying->item, $album, $track);
        self::updateSession($user, Carbon::parse($timestamp_start));
    }

}
