<?php

use Illuminate\Database\Seeder;

class ReweCrowdsourcingVegetariansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 3; $i < 20; $i++) {
            \App\ReweCrowdsourcingVegetarian::updateOrCreate([
                'user_id' => \App\User::all()->random()->id,
                'product_id' => \App\ReweProduct::all()->random()->id
            ], [
                'vegetarian' => rand(-1, 1)
            ]);
        }
    }
}
