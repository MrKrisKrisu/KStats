<?php

namespace App\Http\Controllers\Frontend\Receipt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\Receipt\ImportController as ImportBackend;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Backend\Receipt\Grocy\ReceiptController;

class ImportController extends Controller {

    public function import(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'file' => ['required', 'file']
                                        ]);

        $receipt = ImportBackend::parseReweReceipt(auth()->user(), $validated['file']);

        if(isset(auth()->user()->socialProfile->grocy_host)) {// && $receipt->wasRecentlyCreated == 1) {
            ReceiptController::addReceiptToStock($receipt);
        }

        return redirect()->route('rewe_receipt', ['id' => $receipt->id])
                         ->with('alert-success', 'Der Kassenzettel wurde erfolgreich hochgeladen.');
    }

}
