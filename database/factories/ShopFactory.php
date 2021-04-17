<?php

namespace Database\Factories;

use App\Company;
use App\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class ShopFactory extends Factory {

    protected $model = Shop::class;

    #[ArrayShape([
        'company_id'       => "\Illuminate\Database\Eloquent\Factories\Factory",
        'internal_shop_id' => "int",
        'name'             => "string",
        'address'          => "string",
        'postal_code'      => "string",
        'city'             => "string",
        'osm_type'         => "mixed",
        'osm_id'           => "int"
    ])]
    public function definition(): array {
        return [
            'company_id'       => Company::factory(),
            'internal_shop_id' => $this->faker->unique()->numberBetween(1),
            'name'             => $this->faker->company,
            'address'          => $this->faker->streetAddress,
            'postal_code'      => $this->faker->postcode,
            'city'             => $this->faker->city,
            'osm_type'         => $this->faker->randomElement(['node', 'way', 'relation']),
            'osm_id'           => $this->faker->numberBetween(1000000, 8000000000)
        ];
    }
}
