<?php

namespace Database\Factories;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class ProductTypeFactory extends Factory {

    protected $model = ProductType::class;

    #[ArrayShape([
        'name' => "string"
    ])]
    public function definition(): array {
        return [
            'name' => $this->faker->unique()->word
        ];
    }
}
