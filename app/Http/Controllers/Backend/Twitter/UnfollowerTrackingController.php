<?php

namespace App\Http\Controllers\Backend\Twitter;

use App\Http\Controllers\Controller;
use App\Models\TwitterFollower;
use App\Models\SocialLoginProfile;
use App\Http\Controllers\TwitterApiController;
use App\Models\TwitterUnfollower;
use App\Http\Controllers\TelegramController;
use Carbon\Carbon;
use App\Exceptions\RateLimitException;

abstract class UnfollowerTrackingController extends Controller {

    /**
     * @param TwitterFollower $relationship
     *
     * @throws RateLimitException
     */
    public static function checkUnfollow(TwitterFollower $relationship): void {

        echo 'Checking if ' . $relationship->follower->screen_name . ' is still following ' .
             $relationship->followed->screen_name . PHP_EOL;

        $sl_profile = SocialLoginProfile::where('twitter_id', $relationship->followed_id)->where('twitter_token', '<>', null)->first();
        if($sl_profile === null) {
            echo '**** Social login profile missing for user ' . $relationship->followed->screen_name
                 . ' Skip user for this session.' . PHP_EOL;
            return;
        }

        $connection = TwitterApiController::getNewConnection($sl_profile);
        if(!TwitterApiController::canRequest($sl_profile, 'users/show', 900)) {
            echo '**** API Limit for user ' . $sl_profile->twitter_id
                 . ' exceeded. Skip user for this session.' . PHP_EOL;
            throw new RateLimitException();
        }

        $relationship->update(['last_checked' => Carbon::now()->toIso8601String()]);

        $follower = $connection->get('users/show', ['user_id' => $relationship->follower_id]);
        if(isset($follower->errors)) {
            foreach($follower->errors as $error) {
                if($error->code == 50 || $error->code == 63) { //not found, suspendet
                    echo '**** User ' . $relationship->follower->screen_name . ' is suspended.' .
                         'Handle as unfollow.' . PHP_EOL;
                    self::handleSuspension($relationship);
                    $relationship->delete();
                    return;
                }
            }
        }

        if(!TwitterApiController::canRequest($sl_profile, 'friendships/lookup', 15)) {
            echo '**** API Limit for user ' . $sl_profile->twitter_id
                 . ' exceeded. Skip user for this session.' . PHP_EOL;
            throw new RateLimitException();
        }

        $result = $connection->get("friendships/lookup", ['user_id' => $relationship->follower_id]); //TODO: multiple requests
        TwitterApiController::saveRequest($sl_profile, 'friendships/lookup');

        foreach($result as $real_relationship) {
            if(!isset($real_relationship->connections)) {
                dump($real_relationship);
                dump("No Connection array?");
                continue;
            }

            $followed_by = in_array('followed_by', $real_relationship->connections, false);

            if(!$followed_by) {
                echo '**** User ' . $relationship->follower->screen_name . ' is no longer following ' .
                     $relationship->followed->screen_name . PHP_EOL;

                self::handleUnfollow($relationship);
                return;
            }

            echo "Follow still exists." . PHP_EOL;
            return;
        }
    }

    private static function handleUnfollow(TwitterFollower $relationship): void {

        $timestamp = $relationship->last_checked ?? $relationship->updated_at;

        $twitterUnfollower = TwitterUnfollower::create([
                                                           'account_id'    => $relationship->followed->id,
                                                           'unfollower_id' => $relationship->follower->id,
                                                           'unfollowed_at' => $timestamp
                                                       ]);

        $relationship->delete();

        //TODO: Check if user wants to receive notifications

        TelegramController::sendMessage(
            user:    $twitterUnfollower->twitter_profile->socialProfile->user,
            message: "<b>Neuer Twitter Unfollower</b>\r\n" . $relationship->follower->screen_name .
                     " ist dir etwa " . $timestamp->diffForHumans() . " entfolgt. \r\n\r\n" .
                     "https://twitter.com/" . $relationship->follower->screen_name
        );
    }

    private static function handleSuspension(TwitterFollower $relationship): void {
        $timestamp = $relationship->last_checked ?? $relationship->updated_at;

        $twitterUnfollower = TwitterUnfollower::create([
                                                           'account_id'    => $relationship->followed->id,
                                                           'unfollower_id' => $relationship->follower->id,
                                                           'unfollowed_at' => $timestamp->toIso8601String()
                                                       ]);

        $relationship->delete();

        //TODO: Check if user wants to receive notifications
        return;
        TelegramController::sendMessage(
            user:    $twitterUnfollower->twitter_profile->socialProfile->user,
            message: "<b>Neuer Twitter Unfollower</b>\r\n" .
                     "Das Twitter Profil " . $relationship->follower->screen_name .
                     " wurde eingeschränkt, gesperrt oder gelöscht. \r\n\r\n" .
                     "https://twitter.com/" . $relationship->follower->screen_name
        );
    }
}
