<?php

namespace Database\Seeders;

use App\ReweCrowdsourcingVegetarian;
use App\ReweProduct;
use App\User;
use Illuminate\Database\Seeder;

class ReweCrowdsourcingVegetariansSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        for($i = 3; $i < 20; $i++) {
            ReweCrowdsourcingVegetarian::updateOrCreate([
                                                            'user_id'    => User::all()->random()->id,
                                                            'product_id' => ReweProduct::all()->random()->id
                                                        ], [
                                                            'vegetarian' => rand(-1, 1)
                                                        ]);
        }
    }
}
