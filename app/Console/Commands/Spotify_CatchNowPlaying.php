<?php

namespace App\Console\Commands;

use App\Exceptions\SpotifyTokenExpiredException;
use App\Http\Controllers\SpotifyAPIController;
use App\SocialLoginProfile;
use App\SpotifyAlbum;
use App\SpotifyAlbumArtist;
use App\SpotifyArtist;
use App\SpotifyDevice;
use App\SpotifyPlayActivity;
use App\SpotifySession;
use App\SpotifyTrack;
use App\SpotifyTrackArtist;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Spotify_CatchNowPlaying extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spotify:catchNowPlaying';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Catch the current playing tracks of every user and save to the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slProfile = SocialLoginProfile::whereNotNull('spotify_accessToken')
            ->where('spotify_lastRefreshed', '>', Carbon::parse('-1 hour'))
            ->get();

        foreach ($slProfile as $profile) {
            try {
                $user = $profile->user()->first();
                Log::info("[Spotify] [CatchNowPlaying] Checking User " . $user->id . ' / ' . $user->username);
                dump("[Spotify] [CatchNowPlaying] Checking User " . $user->id . ' / ' . $user->username);

                $nowPlaying = SpotifyAPIController::getNowPlaying($profile->spotify_accessToken);

                if (!$nowPlaying) {
                    Log::info("[Spotify] [CatchNowPlaying] Skipping User " . $user->id . "...");
                    continue;
                }

                if (strpos($nowPlaying->item->uri, 'spotify:local:') !== false) {
                    Log::info('Local tracks are currently not supported.'); //TODO
                    continue;
                }

                $timestamp_start = date('Y-m-d H:i:s', $nowPlaying->timestamp / 1000);
                $track_id = $nowPlaying->item->id;
                $progress_ms = (int)$nowPlaying->progress_ms;
                $context = isset($nowPlaying->context->type) ? $nowPlaying->context->type : NULL;
                $context_uri = isset($nowPlaying->context->uri) ? $nowPlaying->context->uri : NULL;

                $devices = SpotifyAPIController::getDevices($profile->spotify_accessToken);
                $activeDevice = NULL;

                foreach ($devices->devices as $device) {
                    $de = SpotifyDevice::updateOrCreate([
                        'device_id' => $device->id
                    ], [
                        'user_id' => $user->id,
                        'name' => $device->name,
                        'type' => $device->type
                    ]);
                    if ($device->is_active)
                        $activeDevice = $de;
                }

                $track = SpotifyTrack::updateOrCreate(
                    [
                        'track_id' => $nowPlaying->item->id
                    ],
                    [
                        'name' => $nowPlaying->item->name,
                        'album_id' => $nowPlaying->item->album->id,
                        'preview_url' => $nowPlaying->item->preview_url,
                        'popularity' => $nowPlaying->item->popularity,
                        'explicit' => $nowPlaying->item->explicit,
                        'duration_ms' => $nowPlaying->item->duration_ms
                    ]
                );

                SpotifyPlayActivity::create([
                    'user_id' => $user->id,
                    'timestamp_start' => $timestamp_start,
                    'track_id' => $track_id,
                    'progress_ms' => $progress_ms,
                    'context' => $context,
                    'context_uri' => $context_uri,
                    'device_id' => $activeDevice == NULL ? NULL : $activeDevice->id
                ]);

                $album_release_date = $nowPlaying->item->album->release_date;
                if ($nowPlaying->item->album->release_date_precision == 'month')
                    $album_release_date .= "-01";
                else if ($nowPlaying->item->album->release_date_precision == 'year')
                    $album_release_date .= "-01-01";

                $album = SpotifyAlbum::updateOrCreate(
                    [
                        'album_id' => $nowPlaying->item->album->id
                    ],
                    [
                        'name' => $nowPlaying->item->album->name,
                        'imageUrl' => $nowPlaying->item->album->images[0]->url,
                        'release_date' => $album_release_date
                    ]
                );

                foreach ($nowPlaying->item->album->artists as $artist) {
                    $artist = SpotifyArtist::updateOrCreate(
                        [
                            'artist_id' => $artist->id
                        ],
                        [
                            'name' => $artist->name
                        ]
                    );
                    $album->artists()->syncWithoutDetaching($artist);
                }
                foreach ($nowPlaying->item->artists as $artist) {
                    $artist = SpotifyArtist::updateOrCreate(
                        [
                            'artist_id' => $artist->id
                        ],
                        [
                            'name' => $artist->name
                        ]
                    );

                    $artist->tracks()->syncWithoutDetaching($track);
                }

                $session = SpotifySession::where('user_id', $user->id)
                    ->where('timestamp_end', '>', DB::raw('(NOW() - INTERVAL 5 MINUTE)'))
                    ->first();

                if ($session == null) {
                    SpotifySession::create([
                        'user_id' => $user->id,
                        'timestamp_start' => $timestamp_start,
                        'timestamp_end' => Carbon::now()
                    ]);
                } else {
                    $session->timestamp_end = Carbon::now();
                    $session->update();
                }
            } catch (SpotifyTokenExpiredException $e) {
                dump("Access Token expired from User " . $profile->user()->first()->username);
            } catch (\Exception $e) {
                dump($e);
            }
        }

        return 0;
    }

}
