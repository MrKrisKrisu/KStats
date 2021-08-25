<?php

namespace App\Http\Controllers\Frontend\PublicTransport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublicTransportCard;
use Illuminate\Http\RedirectResponse;

class CardController extends Controller {

    public function addNewCard(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'description' => ['required', 'max:255'],
                                            'valid_from'  => ['required', 'date', 'before:valid_to'],
                                            'valid_to'    => ['required', 'date', 'after:valid_from'],
                                            'cost'        => ['required', 'numeric'],
                                        ]);

        $validated['user_id'] = auth()->id();
        PublicTransportCard::create($validated);
        return back()->with('alert-success', __('ptt-card.added'));
    }
}
