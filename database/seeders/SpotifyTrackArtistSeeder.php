<?php

namespace Database\Seeders;

use App\SpotifyArtist;
use App\SpotifyTrack;
use Illuminate\Database\Seeder;

class SpotifyTrackArtistSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        foreach(SpotifyTrack::all() as $track)
            $track->artists()->sync(SpotifyArtist::all()->random(rand(1, 3)));
    }
}
