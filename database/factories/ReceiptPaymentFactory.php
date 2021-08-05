<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\Receipt;
use App\Models\ReceiptPayment;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class ReceiptPaymentFactory extends Factory {

    protected $model = ReceiptPayment::class;

    #[ArrayShape([
        'receipt_id'        => "\Illuminate\Database\Eloquent\Factories\Factory",
        'payment_method_id' => "\Illuminate\Database\Eloquent\Factories\Factory",
        'amount'            => "float"
    ])]
    public function definition(): array {
        return [
            'receipt_id'        => Receipt::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'amount'            => $this->faker->numberBetween(100, 10000) / 100
        ];
    }
}
