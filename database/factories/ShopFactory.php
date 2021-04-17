<?php

namespace Database\Factories;

use App\Company;
use App\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class ShopFactory extends Factory {

    protected $model = Shop::class;

    #[ArrayShape([
        'brand_id'    => "\Illuminate\Database\Eloquent\Factories\Factory",
        'name'        => "string",
        'address'     => "string",
        'postal_code' => "string",
        'city'        => "string",
        'osm_type'    => "mixed",
        'osm_id'      => "int"
    ])]
    public function definition(): array {
        return [
            'brand_id'    => Company::factory(),
            'name'        => $this->faker->company,
            'address'     => $this->faker->streetAddress,
            'postal_code' => $this->faker->postcode,
            'city'        => $this->faker->city,
            'osm_type'    => $this->faker->randomElement(['node', 'way', 'relation']),
            'osm_id'      => $this->faker->numberBetween(1000000, 8000000000)
        ];
    }
}
