<?php

namespace Database\Factories;

use App\SpotifyDevice;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class SpotifyDeviceFactory extends Factory {

    protected $model = SpotifyDevice::class;

    #[ArrayShape(['device_id' => "string", 'user_id' => "\Illuminate\Database\Eloquent\Factories\Factory", 'name' => "string", 'type' => "mixed"])]
    public function definition(): array {
        return [
            'device_id' => $this->faker->unique()->md5,
            'user_id'   => User::factory(),
            'name'      => $this->faker->firstName . "'s " . $this->faker->randomElement(['iPhone', 'Smartphone', 'Smartwatch', 'Chromecast']),
            'type'      => $this->faker->randomElement(['Computer', 'Smartphone', 'Speaker', 'Unknown', 'TV'])
        ];
    }
}
