<?php

namespace App\Console\Commands;

use App\Models\SocialLoginProfile;
use App\Models\TwitterApiRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanUp extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "kstats:cleanup";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = " ";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        TwitterApiRequest::where('created_at', '<', Carbon::now()->addMinutes(-30))->delete();
        SocialLoginProfile::where('spotify_lastRefreshed', '<', Carbon::now()->subHours(6)->toDateTimeString())
                          ->update([
                                       'spotify_accessToken'  => null,
                                       'spotify_refreshToken' => null
                                   ]);
    }

}
