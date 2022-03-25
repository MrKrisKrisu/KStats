<?php

namespace App\Http\Controllers\Frontend\Meter;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MeterManagementController extends Controller {

    public function dashboard(): View {
        return view('meter.dashboard');
    }

    public function view(int $uuid) {

    }
}
