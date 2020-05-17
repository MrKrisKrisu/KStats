<?php

use Illuminate\Database\Seeder;

class SocialLoginProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('de_DE');

        foreach(\App\User::all() as $user) {
            \App\SocialLoginProfile::create([
                'user_id' => $user->id,
                'spotify_user_id' => $faker->userName,
                'spotify_accessToken' => 'example for testing',
                'spotify_refreshToken' => 'example for testing',
                'spotify_lastRefreshed' => \Carbon\Carbon::now()->addMinutes(rand(-30, 0))
            ]);
        }
    }
}
