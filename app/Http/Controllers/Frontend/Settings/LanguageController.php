<?php

namespace App\Http\Controllers\Frontend\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Backend\Settings\LanguageController as LanguageBackend;
use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller {

    public static function updateLanguage(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'locale' => ['required', Rule::in(config('app.supported_locales'))],
                                        ]);

        LanguageBackend::updateLanguage($validated['locale']);

        return back()->with('alert-success', __('settings.alert_set_language'));
    }
}
