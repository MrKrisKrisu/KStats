<?php

namespace Database\Seeders;

use App\SpotifySession;
use App\User;
use Carbon\Carbon;
use Exception;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SpotifySessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $faker = Factory::create('de_DE');

        foreach (User::all() as $user) {
            for ($i = 0; $i < rand(10, 2000); $i++) {
                $time_start = $faker->dateTimeBetween('-2 months');
                SpotifySession::updateOrCreate([
                    'user_id' => $user->id,
                    'timestamp_start' => $time_start
                ], [
                    'timestamp_end' => Carbon::parse($time_start)->addMinutes(rand(5, 120))
                ]);
            }
        }
    }
}
