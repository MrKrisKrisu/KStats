<?php

namespace App\Console\Commands;

use App\Models\SocialLoginProfile;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanUp extends Command {

    protected $signature = "kstats:cleanup";

    public function handle(): int {
        SocialLoginProfile::where('spotify_lastRefreshed', '<', Carbon::now()->subHours(6)->toDateTimeString())
                          ->update([
                                       'spotify_accessToken'  => null,
                                       'spotify_refreshToken' => null
                                   ]);
        return 0;
    }

}
