<?php

namespace Database\Factories;

use App\SpotifyAlbum;
use App\SpotifyTrack;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpotifyTrackFactory extends Factory {

    protected $model = SpotifyTrack::class;

    public function definition(): array {
        return [
            'track_id'         => $this->faker->unique()->md5,
            'name'             => $this->faker->unique()->slug,
            'album_id'         => SpotifyAlbum::all()->random()->album_id, //TODO: eww...
            'explicit'         => rand(0, 10) < 1 ? null : rand(0, 1),
            'popularity'       => rand(0, 100),
            'bpm'              => rand(0, 10) < 1 ? null : rand(50, 200),
            'danceability'     => rand(0, 10) < 1 ? null : rand(0, 999) / 1000,
            'energy'           => rand(0, 10) < 1 ? null : rand(0, 999) / 1000,
            'loudness'         => rand(0, 10) < 1 ? null : rand(-5000, 5000) / 1000,
            'speechiness'      => rand(0, 10) < 1 ? null : rand(0, 999) / 1000,
            'acousticness'     => rand(0, 10) < 1 ? null : rand(0, 999) / 1000,
            'instrumentalness' => rand(0, 10) < 1 ? null : rand(0, 999) / 1000,
            'key'              => rand(0, 10) < 1 ? null : rand(0, 11),
            'mode'             => rand(0, 10) < 1 ? null : rand(0, 1),
            'valence'          => rand(0, 10) < 1 ? null : rand(0, 999) / 1000,
            'duration_ms'      => rand(0, 10) < 1 ? null : rand(10000, 500000),
        ];
    }
}
