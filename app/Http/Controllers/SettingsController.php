<?php

namespace App\Http\Controllers;

use App\Rules\MatchOldPassword;
use App\SocialLoginProfile;
use App\User;
use App\UserEmail;
use App\UserSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $isConnectedToTelegram = UserSettings::get(auth()->user()->id, 'telegramID', '') != '';

        $telegramConnectCode = UserSettings::where('name', 'telegram_connectCode')
            ->where('user_id', auth()->user()->id)
            ->where('updated_at', '>', Carbon::now()->addHours('-1'))
            ->first();

        $emails = UserEmail::where('verified_user_id', auth()->user()->id)->orWhere('unverified_user_id', auth()->user()->id)->get();


        return view('settings', [
            'isConnectedToTwitter' => $isConnectedToTwitter,
            'isConnectedToSpotify' => $isConnectedToSpotify,
            'isConnectedToTelegram' => $isConnectedToTelegram,
            'telegramConnectCode' => $telegramConnectCode,
            'emails' => $emails
        ]);
    }

    public function save(\Illuminate\Http\Request $request)
    {
        if (isset($request->action)) {
            switch ($request->action) {
                case 'createTelegramToken':
                    $connectCode = rand(111111, 999999);
                    UserSettings::set(auth()->user()->id, 'telegram_connectCode', $connectCode);
                    return $this->index();


                case 'addEMail':
                    $email = UserEmail::where('email', $request->email)->first();
                    if ($email != NULL) {
                        dd("Dieser E-Mail Adresse ist bereits einem Accounts zugewiesen. Bitte wende sich an den Support.");
                        //TODO: Schönere Meldung
                    } else {
                        $validated = $request->validate([
                            'email' => ['required', 'email:rfc,dns,spoof']
                        ]);

                        UserEmail::create([
                            'email' => $validated['email'],
                            'unverified_user_id' => Auth::user()->id,
                            'verification_key' => md5(rand(0, 99999) . time() . Auth::user()->id)
                        ]);

                        //TODO: Email Bestätigung senden

                    }
                    return $this->index();
            }
        }
    }

    public function changePassword(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', new MatchOldPassword()],
            'new_password' => ['required',],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($validated['new_password'])]);

        $request->session()->flash('alert-success', __('settings.password.changed_successfully'));
        return back();
    }
}
