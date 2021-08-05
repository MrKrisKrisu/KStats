<?php

namespace App\Http\Controllers\Frontend\Receipt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Backend\Receipt\ImportController as ImportBackend;
use Illuminate\Http\RedirectResponse;

class ImportController extends Controller {

    public function renderImportPage(): View {
        return view('receipt.import');
    }

    public function import(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'file' => ['required', 'file']
                                        ]);

        $receipt = ImportBackend::parseReweReceipt(auth()->user(), $validated['file']);

        return redirect()->route('rewe_receipt', ['id' => $receipt->id])
                         ->with('alert-success', 'Der Kassenzettel wurde erfolgreich hochgeladen.');
    }

}
