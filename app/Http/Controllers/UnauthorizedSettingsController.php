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
            $request->session()->flash('alert-danger', "Der Verifizierungslink ist nicht gültig.");
            return redirect()->route('home');
        }

        $userEmail->verified_user_id = $userEmail->unverified_user_id;
        $userEmail->unverified_user_id = NULL;
        $userEmail->update();
        $request->session()->flash('alert-success', "Die E-Mail Adresse wurde erfolgreich bestätigt.");

        return redirect()->route('home');
    }
}
