<?php

namespace App\Console\Commands;

use App\Models\TwitterFollower;
use Illuminate\Console\Command;
use App\Jobs\CheckTwitterUnfollow;
use App\Models\SocialLoginProfile;

class TwitterCheckUnfollows extends Command {

    protected $signature = 'twitter:check_unfollows';

    public function handle(): int {

        if(!config('app.twitter.crawling')) {
            echo "Twitter crawling currently deactivated." . PHP_EOL;
            return 0;
        }

        $socialProfilesToCheck = SocialLoginProfile::whereNotNull('twitter_id')
                                                   ->whereNotNull('twitter_token')
                                                   ->whereNotNull('twitter_tokenSecret')
                                                   ->get();

        foreach($socialProfilesToCheck as $socialProfile) {
            $toCheck = TwitterFollower::where('followed_id', $socialProfile->twitter_id)
                                      ->orderBy('last_checked')
                                      ->limit(3)
                                      ->get();
            foreach($toCheck as $relationship) {
                CheckTwitterUnfollow::dispatch($relationship);
            }
        }
        return 0;
    }
}

