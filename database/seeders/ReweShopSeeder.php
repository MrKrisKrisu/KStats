<?php

namespace Database\Seeders;

use App\ReweShop;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ReweShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create('de_DE');

        for ($i = 0; $i < rand(4, 10); $i++) {
            ReweShop::create([
                'name' => "REWE " . $faker->lastName . " oHG",
                'address' => $faker->streetAddress,
                'zip' => $faker->postcode,
                'city' => $faker->city,
                'phone' => $faker->phoneNumber,
                'opening_hours' => 'Mo-Sa 07:00 - 23:00 Uhr'
            ]);
        }


    }
}
