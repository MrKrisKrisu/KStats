<?php

namespace App\Console\Commands;

use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TwitterApiController;
use App\SocialLoginProfile;
use App\TwitterFollower;
use App\TwitterUnfollower;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;

class Twitter_CheckUnfollows extends Command {

    protected $signature = 'twitter:check_unfollows {limit=500}';

    public function handle(): int {

        if(!config('app.twitter.crawling')) {
            dump("Twitter crawling currently deactivated.");
            return 0;
        }

        $toCheck = TwitterFollower::orderBy('updated_at', 'asc')->limit($this->argument('limit'))->get();
        foreach($toCheck as $relationship) {
            try {
                $sl_profile = SocialLoginProfile::where('twitter_id', $relationship->followed_id)->where('twitter_token', '<>', null)->first();
                if($sl_profile == null) {
                    echo '**** Social login profile missing for user ' . $relationship->followed->screen_name
                         . ' Skip user for this session.' . PHP_EOL;
                    $toCheck = $toCheck->reject(function($model) use ($sl_profile) {
                        return $model->twitter_id == $sl_profile->twitter_id;
                    });
                    continue;
                }

                $connection = TwitterApiController::getNewConnection($sl_profile);
                if(!TwitterApiController::canRequest($sl_profile, 'users/show', 900)) {
                    $toCheck = $toCheck->reject(function($model) use ($sl_profile) {
                        return $model->twitter_id == $sl_profile->twitter_id;
                    });

                    echo '**** API Limit for user ' . $sl_profile->twitter_id
                         . ' exceeded. Skip user for this session.' . PHP_EOL;
                    continue;
                }

                $follower = $connection->get('users/show', ['user_id' => $relationship->follower_id]);
                if(isset($follower->errors)) {
                    foreach($follower->errors as $error) {
                        if($error->code == 50 || $error->code == 63) { //not found, suspendet
                            dump("User not found -> handle Unfollow");
                            $relationship->delete();

                            TwitterUnfollower::create([
                                                          'account_id'    => $relationship->followed->id,
                                                          'unfollower_id' => $relationship->follower->id,
                                                          'unfollowed_at' => $relationship->updated_at
                                                      ]);
                            TelegramController::sendMessage($sl_profile->user, "<b>Neuer Twitter Unfollower</b>\r\n" . "Das Twitter Profil @" . $relationship->follower->screen_name . ' ist nicht mehr aufrufbar und folgt dir daher nicht mehr.');

                            continue;
                        }
                    }
                }

                if(!TwitterApiController::canRequest($sl_profile, 'friendships/lookup', 15)) {
                    dump("No Requests " . $sl_profile->twitter_id);
                    continue;
                }

                $result = $connection->get("friendships/lookup", ['user_id' => $relationship->follower_id]); //TODO: multiple requests
                TwitterApiController::saveRequest($sl_profile, 'friendships/lookup');

                foreach($result as $real_relationship) {
                    if(!isset($real_relationship->connections)) {
                        dump($real_relationship);
                        dump("No Connection array?");
                        continue;
                    }

                    //$following = in_array('following', $real_relationship->connections);
                    $followed_by = in_array('followed_by', $real_relationship->connections);

                    if(!$followed_by) {
                        dump("User " . $relationship->follower->screen_name . ' hat ' . $relationship->followed->screen_name . ' entfolgt.');
                        $relationship->delete();

                        TwitterUnfollower::create([
                                                      'account_id'    => $relationship->followed->id,
                                                      'unfollower_id' => $relationship->follower->id,
                                                      'unfollowed_at' => $relationship->updated_at
                                                  ]);

                        TelegramController::sendMessage($sl_profile->user, "<b>Neuer Twitter Unfollower</b>\r\n" . $relationship->follower->screen_name . ' ist dir etwa ' . $relationship->updated_at->diffForHumans() . ' entfolgt.');
                    } else {
                        dump("Is following");
                        $relationship->updated_at = Carbon::now();
                        $relationship->update();
                    }
                }
            } catch(Exception $e) {
                dump($e);
                report($e);
            }
        }

        return 0;
    }
}

