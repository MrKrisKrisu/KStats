<?php

namespace App\Console\Commands;

use App\Exceptions\SpotifyAPIException;
use App\Http\Controllers\SpotifyAPIController;
use App\SpotifyTrack;
use Illuminate\Console\Command;

class Spotify_GetTrackInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spotify:getTrackInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves track infos from Spotify API';

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
        $tracks = SpotifyTrack::select('track_id')
            ->orderBy('updated_at', 'asc')
            ->limit(100)
            ->pluck('track_id')
            ->implode(',');
        try {
            $af = SpotifyAPIController::getAudioFeatures($tracks);

            foreach ($af->audio_features as $trackInfo) {
                try {
                    SpotifyTrack::where('track_id', $trackInfo->id)->update(
                        [
                            'danceability' => $trackInfo->danceability,
                            'energy' => $trackInfo->energy,
                            'loudness' => $trackInfo->loudness,
                            'speechiness' => $trackInfo->speechiness,
                            'acousticness' => $trackInfo->acousticness,
                            'instrumentalness' => $trackInfo->instrumentalness,
                            'valence' => $trackInfo->valence,
                            'duration_ms' => $trackInfo->duration_ms,
                            'key' => $trackInfo->key,
                            'mode' => $trackInfo->mode,
                            'bpm' => $trackInfo->tempo
                        ]
                    );
                } catch (\Exception $e) {
                    report($e);
                }
            }
        } catch (SpotifyAPIException $e) {
            report($e);
        }
        return 0;
    }
}
