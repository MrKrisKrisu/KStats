<?php

namespace Database\Seeders;

use App\Models\SpotifyDevice;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SpotifyDeviceSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Factory::create('de_DE');

        foreach(User::all() as $user) {
            for($i = 0; $i < rand(1, 10); $i++) {
                SpotifyDevice::create([
                                          'device_id' => md5($faker->randomAscii . rand(0, 9999) . time()),
                                          'user_id'   => $user->id,
                                          'name'      => $faker->firstName . "'s " . $faker->randomElement(['iPhone', 'Smartphone', 'Smartwatch', 'Chromecast']),
                                          'type'      => $faker->randomElement(['Computer', 'Smartphone', 'Speaker', 'Unknown', 'TV'])
                                      ]);
            }
        }
    }
}
