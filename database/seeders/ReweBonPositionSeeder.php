<?php

namespace Database\Seeders;

use App\ReweBon;
use App\ReweBonPosition;
use App\ReweProduct;
use Illuminate\Database\Seeder;

class ReweBonPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (ReweBon::all() as $bon) {
            for ($i = 0; $i < rand(1, 10); $i++) {
                $amount = rand(0, 4) < 4;
                ReweBonPosition::create([
                    'bon_id' => $bon->id,
                    'product_id' => ReweProduct::all()->random()->id,
                    'amount' => $amount ? rand(1, 10) : NULL,
                    'weight' => $amount ? NULL : rand(1, 1000) / 100,
                    'single_price' => rand(1, 1000) / 100
                ]);
            }
        }
    }
}
