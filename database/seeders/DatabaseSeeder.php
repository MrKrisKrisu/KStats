<?php

namespace Database\Seeders;

use App\SpotifyAlbum;
use App\SpotifyTrack;
use App\User;
use Illuminate\Database\Seeder;
use App\Friendship;

class DatabaseSeeder extends Seeder {

    public function run(): void {
        $user = User::factory()->create(['username' => 'john.doe']);

        foreach(User::factory(rand(3, 9))->create() as $friend) {
            Friendship::create(['user_id' => $user->id, 'friend_id' => $friend->id]);
            Friendship::create(['user_id' => $friend->id, 'friend_id' => $user->id]);
        }

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
        SpotifyAlbum::factory(rand(5, 100))->create();
        $this->call(SpotifyAlbumArtistSeeder::class);
        SpotifyTrack::factory(rand(100, 100))->create();
        $this->call(SpotifyTrackArtistSeeder::class);
        $this->call(SpotifyDeviceSeeder::class);
        $this->call(SpotifyPlayActivitySeeder::class);
        $this->call(SpotifySessionSeeder::class);
    }
}
