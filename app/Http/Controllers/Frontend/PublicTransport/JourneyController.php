<?php

namespace App\Http\Controllers\Frontend\PublicTransport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublicTransportCard;
use Illuminate\Http\RedirectResponse;
use App\Models\PublicTransportJourney;

class JourneyController extends Controller {

    public function addNewJourney(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'public_transport_card_id' => ['required', 'exists:public_transport_cards,id',],
                                            'date'                     => ['required', 'date',],
                                            'origin'                   => ['required', 'max:255',],
                                            'destination'              => ['required', 'max:255',],
                                            'price_without_card'       => ['required', 'numeric'],
                                            'price_with_card'          => ['required', 'numeric'],
                                        ]);

        $card = PublicTransportCard::find($validated['public_transport_card_id']);

        if($card->user_id != auth()->id()) {
            abort(403);
        }
        PublicTransportJourney::create($validated);
        return back()->with('alert-success', __('ptt-journey.added'));
    }
}
