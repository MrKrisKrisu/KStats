<?php

namespace App\Http\Controllers;

use App\Mail\IllnessDepartmentMessage;
use App\Mail\MailVerificationMessage;
use App\Rules\MatchOldPassword;
use App\Models\UserEmail;
use App\Models\UserSettings;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(): Renderable {
        $user = auth()->user();

        $telegramConnectCode = UserSettings::where('name', 'telegram_connectCode')
                                           ->where('user_id', auth()->user()->id)
                                           ->where('updated_at', '>', Carbon::now()->addHours('-1'))
                                           ->first();

        $emails = UserEmail::where('verified_user_id', auth()->user()->id)->orWhere('unverified_user_id', auth()->user()->id)->get();

        return view('settings', [
            'telegramConnectCode' => $telegramConnectCode,
            'emails'              => $emails,
            'user'                => $user
        ]);
    }

    public function save(Request $request): Renderable {
        if(isset($request->action)) {
            switch($request->action) {
                case 'createTelegramToken':
                    $connectCode = rand(111111, 999999);
                    UserSettings::set(auth()->user()->id, 'telegram_connectCode', $connectCode);
                    return $this->index();
            }
        }
    }

    public function addEmail(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'email' => ['required', 'email:rfc,dns,spoof', 'unique:user_emails,email']
                                        ]);

        $userEmail = UserEmail::create([
                                           'email'              => $validated['email'],
                                           'unverified_user_id' => Auth::user()->id,
                                           'verification_key'   => md5(rand(0, 99999) . time() . Auth::user()->id)
                                       ]);

        try {
            self::sendEmailVerification($userEmail);

            return back()->with('alert-success', __('settings.verify_mail.alert_save'));
        } catch(Exception $e) {
            report($e);
            $userEmail->delete();
            return back()->with('alert-danger', __('settings.verify_mail.alert_save_error'));
        }
    }

    /**
     * @param UserEmail $userEmail
     *
     * @return bool
     * @throws Exception
     */
    public static function sendEmailVerification(UserEmail $userEmail): bool {
        Mail::to($userEmail->email)->send(new MailVerificationMessage($userEmail));

        if(Mail::failures())
            throw new Exception("Failure on sending mail: " . json_encode(Mail::failures()));

        return true;
    }

    public function deleteEmail(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'id' => ['required', 'exists:user_emails,id']
                                        ]);

        $userEmail = UserEmail::find($validated['id']);
        if($userEmail->verified_user_id != Auth::user()->id && $userEmail->unverified_user_id != Auth::user()->id) {
            return back()->with('alert-danger', 'Dazu besitzt du nicht die Berechtigung.');
        }

        $userEmail->delete();

        return back()->with('alert-success', 'Die E-Mail Adresse wurde gelöscht.');
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

    public function changePassword(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'current_password'     => ['required', new MatchOldPassword()],
                                            'new_password'         => ['required',],
                                            'new_confirm_password' => ['same:new_password'],
                                        ]);

        Auth::user()->update(['password' => Hash::make($validated['new_password'])]);

        return back()->with('alert-success', __('settings.password.changed_successfully'));
    }

    public function confirmPrivacyPolicy(): RedirectResponse {
        Auth::user()->update([
                                 'privacy_confirmed_at' => Carbon::now()
                             ]);

        return redirect()->route('home')
                         ->with('alert-success', 'Du hast der Datenschutzerkärung erfolgreich zugestimmt. Viel Spaß bei KStats!');
    }
}
