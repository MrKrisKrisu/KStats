<?php

namespace Database\Factories;

use App\SpotifyTrack;
use App\SpotifyTrackRating;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpotifyTrackRatingFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpotifyTrackRating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'user_id'  => User::factory(),
            'track_id' => SpotifyTrack::factory(),
            'rating'   => $this->faker->boolean()
        ];
    }
}
