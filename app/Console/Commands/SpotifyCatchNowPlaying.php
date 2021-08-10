<?php

namespace App\Console\Commands;

use App\Exceptions\SpotifyTokenExpiredException;
use App\Http\Controllers\SpotifyAPIController;
use App\Models\SocialLoginProfile;
use App\Models\SpotifyAlbum;
use App\Models\SpotifyArtist;
use App\Models\SpotifyContext;
use App\Models\SpotifyDevice;
use App\Models\SpotifyPlayActivity;
use App\Models\SpotifySession;
use App\Models\SpotifyTrack;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpotifyCatchNowPlaying extends Command {

    protected $signature   = 'spotify:catchNowPlaying';
    protected $description = 'Catch the current playing tracks of every user and save to the database';

    public function handle(): int {
        $slProfile = SocialLoginProfile::whereNotNull('spotify_accessToken')
                                       ->where('spotify_lastRefreshed', '>', Carbon::parse('-1 hour'))
                                       ->get();

        foreach($slProfile as $profile) {
            try {
                $user = $profile->user()->first();
                dump("[Spotify] [CatchNowPlaying] Checking User " . $user->id . ' / ' . $user->username);

                $nowPlaying = SpotifyAPIController::getNowPlaying($profile->spotify_accessToken);

                if(!$nowPlaying) {
                    continue;
                }

                //TODO: Local tracks are currently not supported.
                if(isset($nowPlaying->item->uri) && str_contains($nowPlaying->item->uri, 'spotify:local:')) {
                    echo '* Local tracks are currently unsupported.' . PHP_EOL;
                    continue;
                }

                //TODO: Support episodes
                if(isset($nowPlaying->currently_playing_type) && $nowPlaying->currently_playing_type == 'episode') {
                    echo '* Episodes are currently unsupported.' . PHP_EOL;
                    continue;
                }

                if(isset($nowPlaying->currently_playing_type) && $nowPlaying->currently_playing_type == 'ad') {
                    echo '* Ads are unsupported.' . PHP_EOL;
                    continue;
                }

                if(!isset($nowPlaying->item->id)) {
                    Log::debug('Error: nowPlaying->item->id not found.');
                    Log::debug(print_r($nowPlaying, true));
                    continue;
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

                $album_release_date = $nowPlaying->item->album->release_date;
                if($nowPlaying->item->album->release_date_precision == 'month') {
                    $album_release_date .= "-01";
                } elseif($nowPlaying->item->album->release_date_precision == 'year') {
                    $album_release_date .= "-01-01";
                }

                $album = SpotifyAlbum::updateOrCreate(
                    [
                        'album_id' => $nowPlaying->item->album->id
                    ], [
                        'name'         => $nowPlaying->item->album->name,
                        'imageUrl'     => $nowPlaying->item->album->images[0]->url,
                        'release_date' => $album_release_date
                    ]
                );

                $track = SpotifyTrack::updateOrCreate(
                    [
                        'track_id' => $nowPlaying->item->id
                    ], [
                        'name'        => $nowPlaying->item->name,
                        'album_id'    => $nowPlaying->item->album->id,
                        'preview_url' => $nowPlaying->item->preview_url,
                        'popularity'  => $nowPlaying->item->popularity,
                        'explicit'    => $nowPlaying->item->explicit,
                        'duration_ms' => $nowPlaying->item->duration_ms
                    ]
                );

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

                foreach($nowPlaying->item->album->artists as $artist) {
                    $artist = SpotifyArtist::updateOrCreate(
                        [
                            'artist_id' => $artist->id
                        ], [
                            'name' => $artist->name
                        ]
                    );
                    $album->artists()->syncWithoutDetaching($artist);
                }
                foreach($nowPlaying->item->artists as $artist) {
                    $artist = SpotifyArtist::updateOrCreate(
                        [
                            'artist_id' => $artist->id
                        ], [
                            'name' => $artist->name
                        ]
                    );

                    $artist->tracks()->syncWithoutDetaching($track);
                }

                $session = SpotifySession::where('user_id', $user->id)
                                         ->where('timestamp_end', '>', DB::raw('(NOW() - INTERVAL 5 MINUTE)'))
                                         ->first();

                if($session == null) {
                    SpotifySession::create([
                                               'user_id'         => $user->id,
                                               'timestamp_start' => $timestamp_start,
                                               'timestamp_end'   => Carbon::now()
                                           ]);
                } else {
                    $session->update([
                                         'timestamp_end' => Carbon::now()
                                     ]);
                }
            } catch(SpotifyTokenExpiredException $exception) {
                dump("Access Token expired from User " . $profile->user()->first()->username);
            } catch(Exception $exception) {
                dump($exception);
                report($exception);
            }
        }

        return 0;
    }

}
