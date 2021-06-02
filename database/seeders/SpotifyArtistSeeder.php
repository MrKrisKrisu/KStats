<?php

namespace Database\Seeders;

use App\Models\SpotifyArtist;
use Faker\Factory;
use Illuminate\Database\Seeder;

class SpotifyArtistSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Factory::create('de_DE');
        for($i = 0; $i < rand(10, 100); $i++) {
            SpotifyArtist::create([
                                      'artist_id' => $faker->md5,
                                      'name'      => $faker->name
                                  ]);
        }
    }
}
