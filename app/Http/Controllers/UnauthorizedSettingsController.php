<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UnauthorizedSettingsController extends Controller {

    public function verifyMail(Request $request, int $userId, string $verificationKey): RedirectResponse {
        $userEmail = UserEmail::where('unverified_user_id', $userId)->where('verification_key', $verificationKey)->first();

        if($userEmail == null) {
            return redirect()->route('home')
                             ->with('alert-danger', __('settings.verify_mail.link_invalid'));
        }

        $user = User::find($userId);
        if($user->email == null)
            $user->update([
                              'email' => $userEmail->email
                          ]);

        $userEmail->update([
                               'verified_user_id'   => $userEmail->unverified_user_id,
                               'unverified_user_id' => null,
                               'verification_key'   => null
                           ]);


        return redirect()->route('home')
                         ->with('alert-success', __('settings.verify_mail.verified_successfully'));
    }
}
