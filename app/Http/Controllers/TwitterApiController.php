<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\SocialLoginProfile;
use App\TwitterApiRequest;
use Carbon\Carbon;

class TwitterApiController extends Controller {
    /**
     * @param SocialLoginProfile $slp
     * @param string $endpoint
     * @param int $maxRequestsPer15Minutes
     * @return bool
     */
    public static function canRequest(SocialLoginProfile $slp, string $endpoint, $maxRequestsPer15Minutes = 15) {
        $cnt = TwitterApiRequest::where('twitter_profile_id', $slp->twitter_id)
                                ->where('endpoint', $endpoint)
                                ->where('created_at', '>', Carbon::now()->addMinutes(-15))
                                ->count();
        return $cnt < $maxRequestsPer15Minutes;
    }

    /**
     * @param SocialLoginProfile $slp
     * @param string $endpoint
     */
    public static function saveRequest(SocialLoginProfile $slp, string $endpoint) {
        TwitterApiRequest::create([
                                      'twitter_profile_id' => $slp->twitter_id,
                                      'endpoint'           => $endpoint
                                  ]);
    }

    /**
     * @param SocialLoginProfile $sl_profile
     * @return TwitterOAuth
     */
    public static function getNewConnection(SocialLoginProfile $sl_profile) {
        return new TwitterOAuth(
            config('services.twitter.client_id'),
            config('services.twitter.client_secret'),
            $sl_profile->twitter_token,
            $sl_profile->twitter_tokenSecret
        );

    }
}
