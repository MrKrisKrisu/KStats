<?php

namespace App\Http\Controllers\Frontend\Spotify;

use App\Http\Controllers\Controller;
use App\Models\SpotifyTrack;
use App\Models\User;
use App\Models\UserSettings;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpotifySocialExploreController extends Controller {
    public function saveTime(Request $request) {
        $validated = $request->validate([
                                            'time' => ['required', 'date_format:H:i']
                                        ]);

        UserSettings::set(auth()->user()->id, 'tg_explore_time', $validated['time']);

        return back()->with('alert-success', 'Okay, du bekommst ab jetzt jeden Tag um ' . $validated['time'] . ' Uhr ein Lied per Telegram vorgeschlagen.');
    }

}
