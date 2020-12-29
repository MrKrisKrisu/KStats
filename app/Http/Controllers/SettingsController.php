<?php

namespace App\Http\Controllers;

use App\Mail\IllnessDepartmentMessage;
use App\Mail\MailVerificationMessage;
use App\Rules\MatchOldPassword;
use App\SocialLoginProfile;
use App\User;
use App\UserEmail;
use App\UserSettings;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

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
        $user = auth()->user();

        $socialProfile = auth()->user()->socialProfile()->first() ?: new SocialLoginProfile;

        $isConnectedToTwitter  = $socialProfile->twitter_token != null;
        $isConnectedToSpotify  = $socialProfile->spotify_accessToken != null;
        $isConnectedToTelegram = UserSettings::get(auth()->user()->id, 'telegramID', '') != '';

        $telegramConnectCode = UserSettings::where('name', 'telegram_connectCode')
                                           ->where('user_id', auth()->user()->id)
                                           ->where('updated_at', '>', Carbon::now()->addHours('-1'))
                                           ->first();

        $emails = UserEmail::where('verified_user_id', auth()->user()->id)->orWhere('unverified_user_id', auth()->user()->id)->get();


        return view('settings', [
            'isConnectedToTwitter'  => $isConnectedToTwitter,
            'isConnectedToSpotify'  => $isConnectedToSpotify,
            'isConnectedToTelegram' => $isConnectedToTelegram,
            'telegramConnectCode'   => $telegramConnectCode,
            'emails'                => $emails,
            'user'                  => $user
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
                                           'email'              => $validated['email'],
                                           'unverified_user_id' => Auth::user()->id,
                                           'verification_key'   => md5(rand(0, 99999) . time() . Auth::user()->id)
                                       ]);

        try {
            Mail::to($userEmail->email)->send(new MailVerificationMessage($userEmail));

            $request->session()->flash('alert-success', __('settings.verify_mail.alert_save'));

            if (Mail::failures())
                throw new \Exception("Failure on sending mail: " . json_encode(Mail::failures()));
        } catch (\Exception $e) {
            report($e);
            $request->session()->flash('alert-danger', __('settings.verify_mail.alert_save_error'));
            $userEmail->delete();
        }

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

    public function deleteTelegramConnection(Request $request): RedirectResponse {
        if(!Auth::user()->socialProfile->isConnectedTelegram) {
            return back()->with('alert-danger', __('settings.telegram.not_connected'));
        }

        Auth::user()->socialProfile->update([
                                                'telegram_id' => null
                                            ]);

        return back()->with('alert-success', __('settings.telegram.connection_removed'));
    }

    public function setLanguage(Request $request)
    {
        $validated = $request->validate([
                                            'locale' => ['required', Rule::in(['de', 'en'])]
                                        ]);

        $user         = User::find(Auth::user()->id);
        $user->locale = $validated['locale'];
        $user->update();

        $request->session()->flash('alert-success', __('settings.alert_set_language'));
        return back();
    }

    public function changePassword(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
                                            'current_password'     => ['required', new MatchOldPassword()],
                                            'new_password'         => ['required',],
                                            'new_confirm_password' => ['same:new_password'],
                                        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($validated['new_password'])]);

        $request->session()->flash('alert-success', __('settings.password.changed_successfully'));
        return back();
    }

    public function confirmPrivacyPolicy()
    {
        $user = Auth::user();
        $user->update([
                          'privacy_confirmed_at' => Carbon::now()
                      ]);

        return redirect()->route('home')
                         ->with('alert-success', 'Du hast der Datenschutzerkärung erfolgreich zugestimmt. Viel Spaß bei KStats!');
    }
}
