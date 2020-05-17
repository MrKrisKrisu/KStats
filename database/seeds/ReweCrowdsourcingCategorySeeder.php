<?php

use Illuminate\Database\Seeder;

class ReweCrowdsourcingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 3; $i < 20; $i++) {
            \App\ReweCrowdsourcingCategory::updateOrCreate([
                'user_id' => \App\User::all()->random()->id,
                'product_id' => \App\ReweProduct::all()->random()->id
            ], [
                'category_id' => \App\ReweProductCategory::all()->random()->id
            ]);
        }
    }
}
