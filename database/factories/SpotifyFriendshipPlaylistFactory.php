<?php

namespace Database\Factories;

use App\SpotifyFriendshipPlaylist;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\User;
use JetBrains\PhpStorm\ArrayShape;

class SpotifyFriendshipPlaylistFactory extends Factory {

    protected $model = SpotifyFriendshipPlaylist::class;

    #[ArrayShape([
        'user_id'        => "\Illuminate\Database\Eloquent\Factories\Factory",
        'friend_id'      => "\Illuminate\Database\Eloquent\Factories\Factory",
        'playlist_id'    => "string",
        'last_refreshed' => "\DateTime"
    ])]
    public function definition(): array {
        return [
            'user_id'        => User::factory(),
            'friend_id'      => User::factory(),
            'playlist_id'    => $this->faker->word,
            'last_refreshed' => $this->faker->dateTime(),
        ];
    }
}
