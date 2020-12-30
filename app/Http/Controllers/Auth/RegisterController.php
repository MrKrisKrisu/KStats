<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SettingsController;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserEmail;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User {
        $user = User::create([
                                 'username'   => $data['username'],
                                 'email'      => $data['email'],
                                 'password'   => Hash::make($data['password']),
                                 'last_login' => Carbon::now()
                             ]);


        $userEmail = UserEmail::create([
                                           'email'              => $user->email,
                                           'unverified_user_id' => $user->id,
                                           'verification_key'   => md5(rand(0, 99999) . time() . $user->id)
                                       ]);

        try {
            SettingsController::sendEmailVerification($userEmail);
        } catch(Exception $e) {
            report($e);
        }
        return $user;
    }
}
