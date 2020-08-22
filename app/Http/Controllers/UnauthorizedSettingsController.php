<?php

namespace App\Http\Controllers;

use App\UserEmail;
use Illuminate\Http\Request;

class UnauthorizedSettingsController extends Controller
{
    public function verifyMail(Request $request, int $userId, string $verificationKey)
    {
        $userEmail = UserEmail::where('unverified_user_id', $userId)->where('verification_key', $verificationKey)->first();
        if ($userEmail == NULL) {
            $request->session()->flash('alert-danger', __('settings.verify_mail.link_invalid'));
            return redirect()->route('home');
        }

        $userEmail->verified_user_id = $userEmail->unverified_user_id;
        $userEmail->unverified_user_id = NULL;
        $userEmail->update();
        $request->session()->flash('alert-success', __('settings.verify_mail.verified_successfully'));

        return redirect()->route('home');
    }
}
