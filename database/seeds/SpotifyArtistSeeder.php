<?php

use Illuminate\Database\Seeder;

class SpotifyArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('de_DE');
        for ($i = 0; $i < rand(10, 100); $i++) {
            \App\SpotifyArtist::create([
                'artist_id' => $faker->md5,
                'name' => $faker->name
            ]);
        }
    }
}
