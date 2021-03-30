<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPrivacyConfirmation {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if(Auth::check() && Auth::user()->privacy_confirmed_at === null) {
            return redirect()->route('legal.privacy_policy');
        }

        return $next($request);
    }
}
