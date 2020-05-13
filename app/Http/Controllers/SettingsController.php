<?php

namespace App\Http\Controllers;

use App\SocialLoginProfile;
use App\UserEmail;
use Illuminate\Support\Facades\Request;

class SettingsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $socialProfile = auth()->user()->socialProfile()->first() ?: new SocialLoginProfile;

        $isConnectedToTwitter = $socialProfile->twitter_token != NULL;
        $isConnectedToSpotify = $socialProfile->spotify_accessToken != NULL;

        $emails = UserEmail::where('verified_user_id', auth()->user()->id)->orWhere('unverified_user_id', auth()->user()->id)->get();

        return view('settings', [
            'isConnectedToTwitter' => $isConnectedToTwitter,
            'isConnectedToSpotify' => $isConnectedToSpotify,
            'emails' => $emails
        ]);
    }

    public function save(\Illuminate\Http\Request $request)
    {
        if (isset($request->addEmail)) {
            $email = UserEmail::where('email', $request->addEmail)->first();
            if ($email != NULL) {
                dd("Dieser E-Mail Adresse ist bereits einem Accounts zugewiesen. Bitte wende sich an den Support.");
                //TODO: Schönere Meldung
            } else {
                $email = UserEmail::create([
                    'email' => $request->addEmail,
                    'unverified_user_id' => auth()->user()->id,
                    'verification_key' => md5(rand(0, 99999) . time() . auth()->user()->id)
                ]);

                //TODO: Email Bestätigung senden

            }

            return $this->index();
        }
    }

}
