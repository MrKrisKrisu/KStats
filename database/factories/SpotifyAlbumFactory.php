<?php

namespace Database\Factories;

use App\SpotifyAlbum;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class SpotifyAlbumFactory extends Factory {

    protected $model = SpotifyAlbum::class;

    #[ArrayShape([
        'album_id'     => "string",
        'name'         => "string",
        'imageUrl'     => "string",
        'release_date' => "\DateTime"
    ])]
    public function definition(): array {
        return [
            'album_id'     => $this->faker->md5,
            'name'         => $this->faker->slug,
            'imageUrl'     => $this->faker->imageUrl(640, 640, 'abstract'),
            'release_date' => $this->faker->dateTimeBetween()
        ];
    }
}
