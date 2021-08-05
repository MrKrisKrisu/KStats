<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class ProductFactory extends Factory {

    protected $model = Product::class;

    #[ArrayShape([
        'name'            => "string",
        'user_id'         => "\Illuminate\Database\Eloquent\Factories\Factory",
        'product_type_id' => "\Illuminate\Database\Eloquent\Factories\Factory"
    ])]
    public function definition(): array {
        return [
            'name'            => $this->faker->word,
            'user_id'         => User::factory(),
            'product_type_id' => ProductType::factory(),
        ];
    }
}
