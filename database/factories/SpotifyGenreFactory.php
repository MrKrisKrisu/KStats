<?php

namespace Database\Factories;

use App\Models\SpotifyGenre;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class SpotifyGenreFactory extends Factory {

    protected $model = SpotifyGenre::class;

    #[ArrayShape([
        'seed'         => "string",
        'display_name' => "string"
    ])]
    public function definition(): array {
        return [
            'seed'         => $this->faker->unique()->word,
            'display_name' => $this->faker->unique()->word,
        ];
    }
}
