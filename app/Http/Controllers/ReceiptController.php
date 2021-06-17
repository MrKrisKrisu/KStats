<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class ReceiptController extends Controller {

    public function renderOverview(): Renderable {
        return view('receipts.overview', [
            'receipts' => auth()->user()->receipts()->orderByDesc('timestamp')->paginate()
        ]);
    }

}
