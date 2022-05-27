<?php

namespace App\Http\Controllers\Frontend\Receipt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\Receipt\ImportController as ImportBackend;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Backend\Receipt\Grocy\ReceiptController;
use Illuminate\Validation\Rule;

class ImportController extends Controller {

    public function import(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'brand' => ['required', Rule::in(['REWE', 'Lidl'])],
                                            'file'  => ['required', 'file']
                                        ]);

        if($validated['brand'] === 'REWE') {
            $receipt = ImportBackend::parseReweReceipt(auth()->user(), $validated['file']);
        } else if($validated['brand'] === 'Lidl') {
            $receipt = ImportBackend::parseLidlReceipt(auth()->user(), $validated['file']);
        }
        if(isset(auth()->user()->socialProfile->grocy_host)) {// && $receipt->wasRecentlyCreated == 1) {
            ReceiptController::addReceiptToStock($receipt);
        }

        return redirect()->route('rewe_receipt', ['id' => $receipt->id])
                         ->with('alert-success', 'Der Kassenzettel wurde erfolgreich hochgeladen.');
    }

}
