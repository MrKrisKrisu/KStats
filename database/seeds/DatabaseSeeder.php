<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(SocialLoginProfileSeeder::class);

        //REWE eBon Analyzer Tables
        $this->call(ReweShopSeeder::class);
        $this->call(ReweBonSeeder::class);
        $this->call(ReweProductSeeder::class);
        $this->call(ReweBonPositionSeeder::class);
        $this->call(ReweProductCategorySeeder::class);
        $this->call(ReweCrowdsourcingCategorySeeder::class);
        $this->call(ReweCrowdsourcingVegetariansSeeder::class);

        //Spotify Tables
        $this->call(SpotifyArtistSeeder::class);
        $this->call(SpotifyAlbumSeeder::class);
        $this->call(SpotifyAlbumArtistSeeder::class);
        $this->call(SpotifyTrackSeeder::class);
        $this->call(SpotifyTrackArtistSeeder::class);
        $this->call(SpotifyDeviceSeeder::class);
        $this->call(SpotifyPlayActivitySeeder::class);
        $this->call(SpotifySessionSeeder::class);
    }
}
