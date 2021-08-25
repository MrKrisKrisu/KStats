<?php

namespace App\Http\Controllers\Frontend\PublicTransport;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PublicTransportController extends Controller {

    public function renderOverview(): View {
        return view('public_transport.main');
    }
}
