<?php

namespace App\Http\Controllers;

use App\Exceptions\RateLimitException;
use App\Exceptions\TwitterException;
use App\Exceptions\TwitterTokenInvalidException;
use App\SocialLoginProfile;
use App\TwitterDaily;
use App\TwitterProfile;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TwitterController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public static function index() {
        $user = User::find(Auth::user()->id);

        if($user->socialProfile->twitterUser == null)
            return view('twitter.notconnected');

        return view('twitter.overview', [
            'twitter_profile' => TwitterProfile::with(['unfollower.unfollower_profile'])->where('id', $user->socialProfile->twitter_id)->first(),
        ]);
    }

    /**
     * @param SocialLoginProfile $slp
     * @return mixed
     * @throws RateLimitException
     * @throws TwitterException
     * @throws TwitterTokenInvalidException
     */
    public static function verifyProfile(SocialLoginProfile $slp): TwitterProfile {
        if(!TwitterApiController::canRequest($slp, 'account/verify_credentials', 75))
            throw new RateLimitException();

        $connection   = TwitterApiController::getNewConnection($slp);
        $profile_data = $connection->get("account/verify_credentials");

        if(isset($profile_data->errors)) {
            foreach($profile_data->errors as $error)
                if($error->code == 89)
                    throw new TwitterTokenInvalidException();
            throw new TwitterException(print_r($profile_data->errors, true));
        }

        TwitterApiController::saveRequest($slp, 'account/verify_credentials');

        $twp = TwitterProfile::updateOrCreate([
                                                  'id' => $profile_data->id
                                              ], [
                                                  'name'             => $profile_data->name,
                                                  'screen_name'      => $profile_data->screen_name,
                                                  'location'         => $profile_data->location,
                                                  'description'      => $profile_data->description,
                                                  'url'              => $profile_data->url,
                                                  'protected'        => $profile_data->protected,
                                                  'followers_count'  => $profile_data->followers_count,
                                                  'friends_count'    => $profile_data->friends_count,
                                                  'listed_count'     => $profile_data->listed_count,
                                                  'statuses_count'   => $profile_data->statuses_count,
                                                  'account_creation' => Carbon::parse($profile_data->created_at)->toIso8601String()
                                              ]);

        TwitterDaily::updateOrCreate([
                                         'profile_id' => $profile_data->id,
                                         'date'       => Carbon::now()->toDateString()
                                     ], [
                                         'follower_count' => $profile_data->followers_count,
                                         'friends_count'  => $profile_data->friends_count,
                                         'statuses_count' => $profile_data->statuses_count,
                                     ]);

        $slp->update(['twitter_id' => $profile_data->id]);

        return $twp;
    }
}
