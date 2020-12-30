<?php

namespace Database\Factories;

use App\SpotifyContext;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class SpotifyContextFactory extends Factory {

    protected $model = SpotifyContext::class;

    #[ArrayShape(['uri' => "string"])]
    public function definition(): array {
        return [
            'uri' => 'spotify:' . $this->faker->randomElement(['playlist', 'album']) . ':' . $this->faker->slug
        ];
    }
}
