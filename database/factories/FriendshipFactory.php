<?php

namespace Database\Factories;

use App\Friendship;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\User;
use JetBrains\PhpStorm\ArrayShape;

class FriendshipFactory extends Factory {

    protected $model = Friendship::class;

    #[ArrayShape([
        'user_id'   => "\Illuminate\Database\Eloquent\Factories\Factory",
        'friend_id' => "\Illuminate\Database\Eloquent\Factories\Factory"
    ])]
    public function definition(): array {
        return [
            'user_id'   => User::factory(),
            'friend_id' => User::factory()
        ];
    }
}
