<?php

namespace App\Console\Commands;

use App\Models\SocialLoginProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use App\Jobs\FetchSpotifyLastPlayed;
use Illuminate\Database\Eloquent\Builder;

class SpotifyCatchNowPlaying extends Command {

    protected $signature   = 'spotify:catchNowPlaying';
    protected $description = 'Catch the current playing tracks of every user and save to the database';

    public function handle(): int {
        $slProfile = SocialLoginProfile::whereNotNull('spotify_accessToken')
                                       ->where('spotify_lastRefreshed', '>', Carbon::now()->subHour()->toIso8601String())
                                       ->where(function(Builder $query) {
                                           $query->where('spotify_last_fetched', '<', Carbon::now()->subMinutes(5)->toIso8601String())
                                                 ->orWhereNull('spotify_last_fetched');
                                       })
                                       ->limit(10)
                                       ->get();

        foreach($slProfile as $profile) {
            try {
                $user = $profile->user()->first();
                FetchSpotifyLastPlayed::dispatch($user);
                echo "Add user #" . $user->id . " to queue.\n";
            } catch(Exception $exception) {
                report($exception);
            }
        }

        return 0;
    }

}
