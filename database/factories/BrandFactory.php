<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory {

    public function definition(): array {
        return [
            'name'        => $this->faker->company,
            'wikidata_id' => $this->faker->unique()->numerify('Q########'),
            'vector_logo' => null,
        ];
    }
}
