<?php

namespace App\Http\Controllers\Frontend\PublicTransport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublicTransportCard;
use Illuminate\Http\RedirectResponse;
use App\Models\PublicTransportJourney;
use App\Models\PublicTransportComplaint;

class ComplaintController extends Controller {

    public function addNewComplaint(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'card_id'     => ['required', 'exists:public_transport_cards,id',],
                                            'journey_id'  => ['required', 'exists:public_transport_journeys,id',],
                                            'description' => ['required',],
                                            'cashback'    => ['required', 'numeric'],
                                        ]);

        $card    = PublicTransportCard::find($validated['card_id']);
        $journey = PublicTransportJourney::find($validated['journey_id']);

        if($card->user_id != auth()->id() || $journey->card->user_id != auth()->id()) {
            abort(403);
        }
        $validated['user_id'] = auth()->id();
        PublicTransportComplaint::create($validated);
        return back()->with('alert-success', __('ptt-complaint.added'));
    }
}
