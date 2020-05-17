<?php

use Illuminate\Database\Seeder;

class ReweBonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('de_DE');

        foreach (\App\User::all() as $user) {
            for ($i = 0; $i < rand(3, 20); $i++) {
                \App\ReweBon::create([
                    'user_id' => $user->id,
                    'shop_id' => \App\ReweShop::all()->random()->id,
                    'timestamp_bon' => \Carbon\Carbon::now()->addDays(rand(-30, -1))->addMinutes(rand(-400, 400)),
                    'bon_nr' => rand(1, 9999),
                    'cashier_nr' => rand(111111, 999999),
                    'cashregister_nr' => rand(1, 20),
                    'paymentmethod' => $this->paymentmethod(),
                    'payed_cashless' => rand(0, 1),
                    'payed_contactless' => rand(0, 1),
                    'total' => rand(1, 10000) / 100,
                    'earned_payback_points' => rand(0, 100),
                    'raw_bon' => 'This is an example document.',
                    'receipt_pdf' => NULL
                ]);
            }
        }
    }

    private function paymentmethod()
    {
        $d = ['Mastercard', 'EC-Cash', 'BAR', 'VISA', 'PAYBACK PAY', 'American Express', 'REWE Guthaben', 'Coupon', 'Maestro', 'Geschenkk.', 'CG_EURO'];
        return $d[rand(0, count($d) - 1)];
    }
}
