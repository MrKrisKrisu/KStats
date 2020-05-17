<?php

use Illuminate\Database\Seeder;

class ReweProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->parents() as $category) {
            \App\ReweProductCategory::create([
                'name' => $category,
                'parent_id' => NULL
            ]);
        }

        foreach ($this->categories() as $category) {
            \App\ReweProductCategory::create([
                'name' => $category,
                'parent_id' => \App\ReweProductCategory::where('parent_id', null)->get()->random()->id
            ]);
        }
    }

    private function parents()
    {
        return ['Getränke', 'Essen', 'NonFood'];
    }

    private function categories()
    {
        return ['Obst & Gemüse', 'Frische & Kühlung', 'Drogerie & Kosmetik', 'Haus, Freizeit & Mode', 'Wein, Spirituosen & Tabak'];
    }
}
