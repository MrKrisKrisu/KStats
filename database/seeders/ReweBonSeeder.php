<?php

namespace Database\Seeders;

use App\Models\ReweBon;
use App\Models\ReweShop;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ReweBonSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Factory::create('de_DE');

        foreach(User::all() as $user) {
            for($i = 0; $i < rand(3, 20); $i++) {
                ReweBon::create([
                                    'user_id'               => $user->id,
                                    'shop_id'               => ReweShop::all()->random()->id,
                                    'timestamp_bon'         => Carbon::now()->addDays(rand(-30, -1))->addMinutes(rand(-400, 400)),
                                    'bon_nr'                => rand(1, 9999),
                                    'cashier_nr'            => rand(111111, 999999),
                                    'cashregister_nr'       => rand(1, 20),
                                    'paymentmethod'         => $faker->randomElement(['Mastercard', 'EC-Cash', 'BAR', 'VISA', 'PAYBACK PAY', 'American Express', 'REWE Guthaben', 'Coupon', 'Maestro', 'Geschenkk.', 'CG_EURO']),
                                    'payed_cashless'        => rand(0, 1),
                                    'payed_contactless'     => rand(0, 1),
                                    'total'                 => rand(1, 10000) / 100,
                                    'earned_payback_points' => rand(0, 100),
                                    'raw_bon'               => 'This is an example document.',
                                    'receipt_pdf'           => null
                                ]);
            }
        }
    }
}
