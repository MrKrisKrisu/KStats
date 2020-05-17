<?php

use Illuminate\Database\Seeder;

class SpotifyAlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('de_DE');
        for ($i = 0; $i < rand(5, 100); $i++) {
            \App\SpotifyAlbum::create([
                'album_id' => $faker->md5,
                'name' => $faker->slug,
                'imageUrl' => $faker->imageUrl(640, 640, 'abstract'),
                'release_date' => $faker->dateTimeBetween()
            ]);
        }
    }
}
