<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SpotifyGenre;
use App\Http\Controllers\Backend\Spotify\SpotifyController;

class SpotifyFetchGenres extends Command {

    protected $signature = 'spotify:fetchGenres';

    public function handle(): int {
        $genres = SpotifyController::getRandomApi()->getGenreSeeds();
        foreach($genres->genres as $genre) {
            SpotifyGenre::firstOrCreate(['seed' => $genre]);
        }
        return 0;
    }

}
