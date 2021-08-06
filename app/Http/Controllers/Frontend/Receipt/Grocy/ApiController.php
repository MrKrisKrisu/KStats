<?php

namespace App\Http\Controllers\Frontend\Receipt\Grocy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Controllers\Backend\Receipt\Grocy\ApiController as GrocyBackend;

class ApiController extends Controller {

    public function renderOverview(): View {

        if(auth()->user()->socialProfile->grocy_host !== null && auth()->user()->socialProfile->grocy_key !== null) {
            $systemInfo = GrocyBackend::getSystemInfo(auth()->user());
        }

        return view('grocy.overview', [
            'systemInfo' => $systemInfo ?? null,
        ]);
    }

    public function disconnect(): RedirectResponse {
        $socialProfile = auth()->user()->socialProfile;

        if($socialProfile->grocy_host == null && $socialProfile->grocy_key == null) {
            return back()->with('alert-danger', 'Du bist nicht mit Grocy verbunden.');
        }

        $socialProfile->update([
                                   'grocy_host' => null,
                                   'grocy_key'  => null,
                               ]);

        return back()->with('alert-success', 'Die Verbindung zu Grocy wurde gelöscht.');
    }

    public function connect(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'host'   => ['required'],
                                            'apiKey' => ['required'],
                                        ]);

        $socialProfile = auth()->user()->socialProfile;
        if($socialProfile->grocy_host !== null | $socialProfile->grocy_key !== null) {
            return back()->with('alert-danger', 'Du bist bereits mit Grocy verbunden. Bitte lösche diese Verbindung erst.');
        }

        //TODO: check instance

        $socialProfile->update([
                                   'grocy_host' => $validated['host'],
                                   'grocy_key'  => $validated['apiKey'],
                               ]);

        return back()->with('alert-success', 'Die Verbindung zu Grocy wurde hergestellt.');
    }

}
