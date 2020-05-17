<?php

use App\User;
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
        User::create([
            'username' => 'john.doe',
            'email' => 'dev@k118.de',
            'password' => Hash::make('password'),
        ]);
    }
}
