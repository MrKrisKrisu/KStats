<?php

use Illuminate\Database\Seeder;

class SpotifyTrackArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\SpotifyTrack::all() as $track)
            $track->artists()->sync(\App\SpotifyArtist::all()->random(rand(1, 3)));
    }
}
