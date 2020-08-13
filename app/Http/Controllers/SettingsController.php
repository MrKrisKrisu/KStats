<?php

namespace App\Http\Controllers;

use App\Mail\IllnessDepartmentMessage;
use App\Mail\MailVerificationMessage;
use App\SocialLoginProfile;
use App\User;
use App\UserEmail;
use App\UserSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

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

    public function save(Request $request)
    {
        if (isset($request->action)) {
            switch ($request->action) {
                case 'createTelegramToken':
                    $connectCode = rand(111111, 999999);
                    UserSettings::set(auth()->user()->id, 'telegram_connectCode', $connectCode);
                    return $this->index();
            }
        }
    }

    public function addEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc,dns,spoof', 'unique:user_emails,email']
        ]);

        $userEmail = UserEmail::create([
            'email' => $validated['email'],
            'unverified_user_id' => Auth::user()->id,
            'verification_key' => md5(rand(0, 99999) . time() . Auth::user()->id)
        ]);

        Mail::to($userEmail->email)->send(new MailVerificationMessage($userEmail));

        $request->session()->flash('alert-success', "Die E-Mail Adresse wurde gespeichert. Du solltest gleich eine E-Mail mit einem Bestätigungslink erhalten.");

        return back();
    }

    public function deleteEmail(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'exists:user_emails,id']
        ]);

        $userEmail = UserEmail::find($validated['id']);
        if ($userEmail->verified_user_id != Auth::user()->id && $userEmail->unverified_user_id != Auth::user()->id) {
            $request->session()->flash('alert-success', "Dazu besitzt du nicht die Berechtigung.");
            return back();
        }

        $userEmail->delete();
        $request->session()->flash('alert-success', "Die E-Mail Adresse wurde gelöscht.");

        return back();
    }

    public function deleteTelegramConnection(Request $request)
    {
        $setting = User::find(Auth::user()->id)->settings->where('name', 'telegramID')->first();
        if ($setting == NULL) {
            $request->session()->flash('alert-danger', __('settings.telegram.not_connected'));
            return back();
        }
        $setting->delete();
        $request->session()->flash('alert-success', __('settings.telegram.connection_removed'));
        return back();
    }

}
