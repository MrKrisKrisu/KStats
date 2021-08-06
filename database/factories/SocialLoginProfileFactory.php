<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SocialLoginProfile;
use Carbon\Carbon;
use App\Models\User;

class SocialLoginProfileFactory extends Factory {

    protected $model = SocialLoginProfile::class;

    public function definition(): array {
        return [
            'user_id'               => User::factory(),
            'telegram_id'           => null,
            'twitter_id'            => null,
            'twitter_token'         => null,
            'twitter_tokenSecret'   => null,
            'spotify_user_id'       => $this->faker->unique()->userName,
            'spotify_accessToken'   => 'example for testing',
            'spotify_refreshToken'  => 'example for testing',
            'spotify_lastRefreshed' => Carbon::now()->addMinutes(rand(-30, 0))->toIso8601String(),
            'grocy_host'            => null,
            'grocy_key'             => null
        ];
    }
}
