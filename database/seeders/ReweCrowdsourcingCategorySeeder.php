<?php

namespace Database\Seeders;

use App\Models\ReweCrowdsourcingCategory;
use App\Models\ReweProduct;
use App\Models\ReweProductCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReweCrowdsourcingCategorySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        for($i = 3; $i < 20; $i++) {
            ReweCrowdsourcingCategory::updateOrCreate([
                                                          'user_id'    => User::all()->random()->id,
                                                          'product_id' => ReweProduct::all()->random()->id
                                                      ], [
                                                          'category_id' => ReweProductCategory::all()->random()->id
                                                      ]);
        }
    }
}
