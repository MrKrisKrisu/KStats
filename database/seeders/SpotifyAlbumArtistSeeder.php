<?php

namespace Database\Seeders;

use App\Models\SpotifyAlbum;
use App\Models\SpotifyArtist;
use Illuminate\Database\Seeder;

class SpotifyAlbumArtistSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        foreach(SpotifyAlbum::all() as $album)
            $album->artists()->sync(SpotifyArtist::all()->random(rand(1, 3)));
    }
}
