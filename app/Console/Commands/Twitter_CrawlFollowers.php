<?php

namespace App\Console\Commands;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Exceptions\RateLimitException;
use App\Exceptions\TwitterException;
use App\Exceptions\TwitterTokenInvalidException;
use App\Http\Controllers\TwitterApiController;
use App\Http\Controllers\TwitterController;
use App\SocialLoginProfile;
use App\TwitterFollower;
use App\TwitterProfile;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Twitter_CrawlFollowers extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:crawl_followers';

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        if(!config('app.twitter.crawling')) {
            dump("Twitter crawling currently deactivated.");
            return;
        }

        $sl_profiles = SocialLoginProfile::where('twitter_token', '<>', null)->get();
        foreach($sl_profiles as $sl_profile) {
            try {
                $connection = TwitterApiController::getNewConnection($sl_profile);

                $profile = TwitterController::verifyProfile($sl_profile);
                $this->crawlFollowers($connection, $profile, $sl_profile);
            } catch(RateLimitException $e) {
                echo "Skipping Request due to rate limiting... \r\n";
            } catch(TwitterException $e) {
                echo "Twitter Exception \r\n";
                report($e);
            } catch(TwitterTokenInvalidException $e) {
                echo "Twitter Token from User " . $sl_profile->user_id . " invalid or expired... \r\n";
                $sl_profile->update([
                                        'twitter_id'          => null,
                                        'twitter_token'       => null,
                                        'twitter_tokenSecret' => null,
                                    ]);
                report($e);
            } catch(\Exception $e) {
            }
        }
    }

    /**
     * @param TwitterOAuth       $connection
     * @param TwitterProfile     $twp
     * @param SocialLoginProfile $sl_profile
     * @param array              $parameters
     *
     * @return bool
     * @throws \Exception
     */
    private function crawlFollowers(TwitterOAuth $connection, TwitterProfile $twp, SocialLoginProfile $sl_profile, array $parameters = []) {
        $parameters['count'] = 200;       //max amount
        $parameters['skip_status'] = 1;   //we don't need the tweets

        if(!TwitterApiController::canRequest($sl_profile, 'followers/list', 15)) {
            //TODO: Queue instead of exit...
            dump("Limit exceeded.");
            return false;
        }

        $follower_list = $connection->get("followers/list", $parameters);
        TwitterApiController::saveRequest($sl_profile, 'followers/list');

        if($connection->getLastHttpCode() !== 200) {
            dump($follower_list);
            return false;
        }

        foreach($follower_list->users as $follower) {
            $follower = TwitterProfile::updateOrCreate([
                                                           'id' => $follower->id
                                                       ], [
                                                           'name'             => $follower->name,
                                                           'screen_name'      => $follower->screen_name,
                                                           'location'         => $follower->location,
                                                           'description'      => $follower->description,
                                                           'url'              => $follower->url,
                                                           'protected'        => $follower->protected,
                                                           'followers_count'  => $follower->followers_count,
                                                           'friends_count'    => $follower->friends_count,
                                                           'listed_count'     => $follower->listed_count,
                                                           'statuses_count'   => $follower->statuses_count,
                                                           'account_creation' => Carbon::parse($follower->created_at),
                                                           'updated_at'       => Carbon::now()
                                                       ]);

            TwitterFollower::updateOrCreate([
                                                'follower_id' => $follower->id,
                                                'followed_id' => $twp->id
                                            ], [
                                                'updated_at' => Carbon::now()
                                            ]);

            dump($follower->screen_name);

        }

        if($follower_list->next_cursor != null)
            $this->crawlFollowers($connection, $twp, $sl_profile, ['cursor' => $follower_list->next_cursor]);

        return true;
    }
}
