<?php

namespace App\Http\Controllers;

use App\SocialLoginProfile;
use App\TwitterProfile;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwitterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function index()
    {
        $user = User::find(Auth::user()->id);

        if ($user->socialProfile->twitterUser == NULL)
            return view('twitter.notconnected');

        return view('twitter.overview', [
            'twitter_profile' => TwitterProfile::with(['unfollower.unfollower_profile'])->where('id', $user->socialProfile->twitter_id)->first(),
        ]);
    }

    /**
     * @param SocialLoginProfile $slp
     * @return mixed
     * @throws \Exception
     */
    public static function verifyProfile(SocialLoginProfile $slp)
    {
        if (!TwitterApiController::canRequest($slp, 'account/verify_credentials', 75))
            return false;
        try {
            $connection = TwitterApiController::getNewConnection($slp);
            $profile_data = $connection->get("account/verify_credentials");
            TwitterApiController::saveRequest($slp, 'account/verify_credentials');

            $twp = TwitterProfile::updateOrCreate([
                'id' => $profile_data->id
            ], [
                'name' => $profile_data->name,
                'screen_name' => $profile_data->screen_name,
                'location' => $profile_data->location,
                'description' => $profile_data->description,
                'url' => $profile_data->url,
                'protected' => $profile_data->protected,
                'followers_count' => $profile_data->followers_count,
                'friends_count' => $profile_data->friends_count,
                'listed_count' => $profile_data->listed_count,
                'statuses_count' => $profile_data->statuses_count,
                'account_creation' => Carbon::parse($profile_data->created_at)
            ]);


            $slp->twitter_id = $profile_data->id;
            $slp->update();

            return $twp;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
