<?php

namespace Database\Seeders;

use App\SpotifyDevice;
use App\SpotifyPlayActivity;
use App\SpotifyTrack;
use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SpotifyPlayActivitySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Factory::create('de_DE');

        foreach(User::all() as $user) {
            for($i = 0; $i < rand(1000, 2000); $i++) {
                SpotifyPlayActivity::factory()->create([
                                                           'user_id'   => $user->id,
                                                           'track_id'  => SpotifyTrack::all()->random()->track_id, //TODO: Use database id instead of spotify id
                                                           'device_id' => SpotifyDevice::where('user_id', $user->id)->get()->random()->id,
                                                       ]);
            }
        }
    }
}
