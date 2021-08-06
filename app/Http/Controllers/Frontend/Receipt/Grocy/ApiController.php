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
                                            'host'   => ['required', 'active_url', 'min:9'],
                                            'apiKey' => ['required'],
                                        ]);

        $socialProfile = auth()->user()->socialProfile;
        if($socialProfile->grocy_host !== null | $socialProfile->grocy_key !== null) {
            return back()->with('alert-danger', 'Du bist bereits mit Grocy verbunden. Bitte lösche diese Verbindung erst.');
        }

        if(substr($validated['host'], 0, 4) != 'http') {
            return back()->with('alert-danger', 'Der Hostname muss mit http:// oder https:// beginnen.');
        }
        if(substr($validated['host'], -1, 1) == '/') {
            return back()->with('alert-danger', 'Der Hostname darf nicht mit einem Schrägstrich enden.');
        }

        $systemInfo = GrocyBackend::getSystemInfoWithAuth($validated['host'], $validated['apiKey']);

        if(!isset($systemInfo->grocy_version->Version)) {
            return back()->with('alert-danger', 'Wir konnten uns mit den Daten nicht mit einer grocy-Instanz verbinden.');
        }

        $socialProfile->update([
                                   'grocy_host' => $validated['host'],
                                   'grocy_key'  => $validated['apiKey'],
                               ]);

        return back()->with('alert-success', 'Die Verbindung zu deiner grocy v' . $systemInfo->grocy_version->Version . ' Installation wurde hergestellt.');
    }

}
