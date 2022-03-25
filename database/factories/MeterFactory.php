<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class MeterFactory extends Factory {

    public function definition(): array {
        return [
            'uuid'    => $this->faker->unique()->uuid,
            'user_id' => User::factory(),
            'name'    => $this->faker->word,
            'keyword' => $this->faker->word,
        ];
    }
}
