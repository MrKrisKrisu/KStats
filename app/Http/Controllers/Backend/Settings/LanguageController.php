<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Illuminate\Support\Facades\App;

abstract class LanguageController extends Controller {

    public static function updateLanguage(string $locale): void {
        if(!in_array($locale, config('app.supported_locales'), true)) {
            throw new InvalidArgumentException('Invalid language');
        }

        Auth::user()->update(['locale' => $locale]);
        App::setLocale($locale);
    }
}
