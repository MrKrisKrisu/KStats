<?php

use Illuminate\Database\Seeder;

class ReweProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('de_DE');

        foreach ($this->exampleProducts() as $product) {
            \App\ReweProduct::create([
                'name' => $product["name"],
                'hide' => $product["hide"]
            ]);
        }
    }

    private function exampleProducts()
    {
        return [
            ['name' => 'PFAND', 'hide' => 1],
            ['name' => 'LEERG. MW V. ST', 'hide' => 1],
            ['name' => 'SALATGURKE', 'hide' => 0],
            ['name' => 'MOZZARELLA', 'hide' => 0],
            ['name' => 'COLA', 'hide' => 0],
            ['name' => 'KAESEBROETCHEN', 'hide' => 0],
            ['name' => 'ZITRONE', 'hide' => 0],
            ['name' => 'H-MILCH 3,5%', 'hide' => 0],
            ['name' => 'SPEISEQUARK 20%', 'hide' => 0],
            ['name' => 'SONNTAGSBROETCHE', 'hide' => 0],
            ['name' => 'GOUDA JUNG', 'hide' => 0],
            ['name' => 'BUTTER', 'hide' => 0],
            ['name' => 'WASSER', 'hide' => 0],
            ['name' => 'BROT', 'hide' => 0],
        ];
    }
}
