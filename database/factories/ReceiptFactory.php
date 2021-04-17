<?php

namespace Database\Factories;

use App\Receipt;
use App\Shop;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class ReceiptFactory extends Factory {

    protected $model = Receipt::class;

    #[ArrayShape([
        'user_id'               => "\Illuminate\Database\Eloquent\Factories\Factory",
        'shop_id'               => "\Illuminate\Database\Eloquent\Factories\Factory",
        'timestamp'             => "\DateTime",
        'receipt_nr'            => "int",
        'cashier_nr'            => "int",
        'cash_register_nr'      => "int",
        'amount'                => "float",
        'earned_loyalty_points' => "int",
        'raw_receipt'           => "null"
    ])]
    public function definition(): array {
        return [
            'user_id'               => User::factory(),
            'shop_id'               => Shop::factory(),
            'timestamp'             => $this->faker->dateTimeThisYear,
            'receipt_nr'            => $this->faker->numberBetween(0, 99999),
            'cashier_nr'            => $this->faker->numberBetween(0, 99999),
            'cash_register_nr'      => $this->faker->numberBetween(0, 99),
            'amount'                => $this->faker->numberBetween(100, 10000) / 100,
            'earned_loyalty_points' => $this->faker->numberBetween(0, 100),
            'raw_receipt'           => null
        ];
    }
}