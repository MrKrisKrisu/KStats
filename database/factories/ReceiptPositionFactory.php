<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptPosition;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class ReceiptPositionFactory extends Factory {

    protected $model = ReceiptPosition::class;

    #[ArrayShape([
        'receipt_id'   => "\Illuminate\Database\Eloquent\Factories\Factory",
        'product_id'   => "\Illuminate\Database\Eloquent\Factories\Factory",
        'amount'       => "int|null",
        'weight'       => "float|null",
        'single_price' => "float"
    ])]
    public function definition(): array {
        $rand = $this->faker->boolean;
        return [
            'receipt_id'   => Receipt::factory(),
            'product_id'   => Product::factory(),
            'amount'       => $rand ? $this->faker->numberBetween(1, 10) : null,
            'weight'       => $rand ? null : $this->faker->numberBetween(1, 100000) / 1000,
            'single_price' => $this->faker->numberBetween(100, 10000) / 100
        ];
    }
}
