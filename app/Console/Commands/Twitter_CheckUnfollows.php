<?php

namespace App\Console\Commands;

use App\Http\Controllers\TwitterApiController;
use App\Http\Controllers\TwitterController;
use App\SocialLoginProfile;
use App\TwitterFollower;
use App\TwitterUnfollower;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Twitter_CheckUnfollows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:check_unfollows';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The requests are not designed the best. We can request 100 connections per request -> TODO
     *
     * @return mixed
     */
    public function handle()
    {

        if (!env('TWITTER_CRAWL')) {
            dump("Twitter crawling currently deactivated.");
            return;
        }

        $toCheck = TwitterFollower::orderBy('updated_at', 'asc')->limit(5)->get();
        foreach ($toCheck as $relationship) {
            try {
                $sl_profile = SocialLoginProfile::where('twitter_id', $relationship->followed_id)->first();
                if ($sl_profile == NULL)
                    continue;

                if (!TwitterApiController::canRequest($sl_profile, 'friendships/lookup', 15))
                    continue;

                $connection = TwitterApiController::getNewConnection($sl_profile);
                $result = $connection->get("friendships/lookup", ['user_id' => $relationship->follower_id]); //TODO: multiple requests
                TwitterApiController::saveRequest($sl_profile, 'friendships/lookup');

                foreach ($result as $real_relationship) {
                    if (!isset($real_relationship->connections)) {
                        dump($real_relationship);
                        dump("No Connection array?");
                        continue;
                    }

                    //$following = in_array('following', $real_relationship->connections);
                    $followed_by = in_array('followed_by', $real_relationship->connections);

                    if (!$followed_by) {
                        dump("User " . $relationship->follower->screen_name . ' hat ' . $relationship->followed->screen_name . ' entfolgt.');

                        $relationship->delete();

                        TwitterUnfollower::create([
                            'account_id' => $relationship->followed->id,
                            'unfollower_id' => $relationship->follower->id
                        ]);
                    } else {
                        $relationship->updated_at = Carbon::now();
                        $relationship->update();
                    }
                }
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}

