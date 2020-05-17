<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('de_DE');

        DB::table('users')->insert([
            'username' => 'john.doe',
            'email' => 'dev@k118.de',
            'password' => Hash::make('password'),
        ]);
    }
}
