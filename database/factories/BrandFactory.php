<?php

namespace Database\Factories;

use App\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class BrandFactory extends Factory {

    protected $model = Company::class;

    #[ArrayShape([
        'name'        => "string",
        'wikidata_id' => "string"
    ])]
    public function definition(): array {
        return [
            'name'        => $this->faker->company,
            'wikidata_id' => 'Q' . $this->faker->numberBetween(0, 10000000)
        ];
    }
}
