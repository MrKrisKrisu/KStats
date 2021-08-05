<?php

namespace App\Console\Commands;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Exceptions\RateLimitException;
use App\Exceptions\TwitterException;
use App\Exceptions\TwitterTokenInvalidException;
use App\Http\Controllers\TwitterApiController;
use App\Http\Controllers\TwitterController;
use App\Models\SocialLoginProfile;
use App\Models\TwitterFollower;
use App\Models\TwitterProfile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;

class TwitterCrawlFollowers extends Command {

    protected $signature = 'twitter:crawl_followers';

    public function handle(): int {
        if(!config('app.twitter.crawling')) {
            echo "Twitter crawling currently deactivated." . PHP_EOL;
            return 0;
        }

        $sl_profiles = SocialLoginProfile::where('twitter_token', '<>', null)->get();
        foreach($sl_profiles as $sl_profile) {
            try {
                $connection = TwitterApiController::getNewConnection($sl_profile);

                $profile = TwitterController::verifyProfile($sl_profile);
                $this->crawlFollowers($connection, $profile, $sl_profile);
            } catch(RateLimitException) {
                echo "Skipping Request due to rate limiting..." . PHP_EOL;
            } catch(TwitterException $e) {
                echo "Twitter Exception" . PHP_EOL;
                report($e);
            } catch(TwitterTokenInvalidException $e) {
                echo "Twitter Token from User " . $sl_profile->user_id . " invalid or expired..." . PHP_EOL;
                $sl_profile->update([
                                        'twitter_id'          => null,
                                        'twitter_token'       => null,
                                        'twitter_tokenSecret' => null,
                                    ]);
                report($e);
            } catch(Exception $e) {
                echo "Unknown Exception thrown: " . $e->getMessage() . PHP_EOL;
            }
        }
        return 0;
    }

    /**
     * @param TwitterOAuth       $connection
     * @param TwitterProfile     $twp
     * @param SocialLoginProfile $sl_profile
     * @param array              $parameters
     *
     * @return bool
     * @throws Exception
     */
    private function crawlFollowers(TwitterOAuth $connection, TwitterProfile $twp, SocialLoginProfile $sl_profile, array $parameters = []): bool {
        $parameters['count']       = 200;       //max amount
        $parameters['skip_status'] = 1;         //we don't need the tweets

        if(!TwitterApiController::canRequest($sl_profile, 'followers/list', 15)) {
            //TODO: Queue instead of exit...
            echo "Limit exceeded." . PHP_EOL;
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
                                                           'name'              => $follower->name,
                                                           'screen_name'       => $follower->screen_name,
                                                           'location'          => $follower->location,
                                                           'description'       => $follower->description,
                                                           'url'               => $follower->url,
                                                           'profile_image_url' => $follower?->profile_image_url,
                                                           'protected'         => $follower->protected,
                                                           'followers_count'   => $follower->followers_count,
                                                           'friends_count'     => $follower->friends_count,
                                                           'listed_count'      => $follower->listed_count,
                                                           'statuses_count'    => $follower->statuses_count,
                                                           'account_creation'  => Carbon::parse($follower->created_at),
                                                           'updated_at'        => Carbon::now()->toIso8601String()
                                                       ]);

            TwitterFollower::updateOrCreate([
                                                'follower_id' => $follower->id,
                                                'followed_id' => $twp->id
                                            ], [
                                                'updated_at' => Carbon::now()
                                            ]);

            echo $twp->screen_name . ' -> ' . $follower->screen_name . PHP_EOL;

        }

        if($follower_list->next_cursor != null) {
            $this->crawlFollowers($connection, $twp, $sl_profile, ['cursor' => $follower_list->next_cursor]);
        }

        return true;
    }
}
