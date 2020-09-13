<?php

namespace Database\Seeders;

use App\SocialLoginProfile;
use App\User;
use Carbon\Carbon;
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

        foreach (User::all() as $user) {
            SocialLoginProfile::create([
                'user_id' => $user->id,
                'spotify_user_id' => $faker->userName,
                'spotify_accessToken' => 'example for testing',
                'spotify_refreshToken' => 'example for testing',
                'spotify_lastRefreshed' => Carbon::now()->addMinutes(rand(-30, 0))
            ]);
        }
    }
}
