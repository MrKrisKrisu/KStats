<?php

use Illuminate\Database\Seeder;

class SpotifyAlbumArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\SpotifyAlbum::all() as $album)
            $album->artists()->sync(\App\SpotifyArtist::all()->random(rand(1, 3)));
    }
}
