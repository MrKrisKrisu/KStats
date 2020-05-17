<?php

use App\SpotifyAlbum;
use App\SpotifyTrack;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SpotifyTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create('de_DE');
        for ($i = 0; $i < rand(100, 1000); $i++) {
            SpotifyTrack::create([
                'track_id' => $faker->md5,
                'name' => $faker->slug,
                'album_id' => SpotifyAlbum::all()->random()->album_id, //TODO: use "real" ID instead of spotify ID
                'explicit' => rand(0, 10) < 1 ? NULL : rand(0, 1),
                'popularity' => rand(0, 100),
                'bpm' => rand(0, 10) < 1 ? NULL : rand(50, 200),
                'danceability' => rand(0, 10) < 1 ? NULL : rand(0, 999) / 1000,
                'energy' => rand(0, 10) < 1 ? NULL : rand(0, 999) / 1000,
                'loudness' => rand(0, 10) < 1 ? NULL : rand(-5000, 5000) / 1000,
                'speechiness' => rand(0, 10) < 1 ? NULL : rand(0, 999) / 1000,
                'acousticness' => rand(0, 10) < 1 ? NULL : rand(0, 999) / 1000,
                'instrumentalness' => rand(0, 10) < 1 ? NULL : rand(0, 999) / 1000,
                'key' => rand(0, 10) < 1 ? NULL : rand(0, 11),
                'mode' => rand(0, 10) < 1 ? NULL : rand(0, 1),
                'valence' => rand(0, 10) < 1 ? NULL : rand(0, 999) / 1000,
                'duration_ms' => rand(0, 10) < 1 ? NULL : rand(10000, 500000),
            ]);
        }
    }
}
