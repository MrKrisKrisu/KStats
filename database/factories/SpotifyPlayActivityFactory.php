<?php

namespace Database\Factories;

use App\SpotifyContext;
use App\SpotifyDevice;
use App\SpotifyPlayActivity;
use App\SpotifyTrack;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class SpotifyPlayActivityFactory extends Factory {

    protected $model = SpotifyPlayActivity::class;


    #[ArrayShape([
        'user_id'         => "\Illuminate\Database\Eloquent\Factories\Factory",
        'timestamp_start' => "\DateTime",
        'track_id'        => "\Illuminate\Database\Eloquent\Factories\Factory",
        'progress_ms'     => "int",
        'context'         => "\Illuminate\Database\Eloquent\Factories\Factory",
        'device_id'       => "\Illuminate\Database\Eloquent\Factories\Factory",
        'created_at'      => "\DateTime",
        'updated_at'      => "\DateTime"
    ])]
    public function definition(): array {
        $time = $this->faker->unique()->dateTimeBetween('-2 months');
        return [
            'user_id'         => User::factory(),
            'timestamp_start' => $time,
            'track_id'        => SpotifyTrack::factory(),
            'progress_ms'     => rand(0, 100000),
            'context_id'      => SpotifyContext::factory(),
            'device_id'       => SpotifyDevice::factory(),
            'created_at'      => $time,
            'updated_at'      => $time
        ];
    }
}
