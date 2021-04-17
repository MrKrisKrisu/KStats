<?php

namespace Database\Factories;

use App\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class PaymentMethodFactory extends Factory {

    protected $model = PaymentMethod::class;

    #[ArrayShape([
        'name' => "string"
    ])]
    public function definition(): array {
        return [
            'name' => $this->faker->unique()->word
        ];
    }
}
