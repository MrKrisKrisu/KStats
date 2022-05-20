<?php

namespace App\Http\Controllers\Frontend\Meter;

use App\Http\Controllers\Controller;
use App\Models\Meter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MeterManagementController extends Controller {

    public function dashboard(): View {
        return view('meter.dashboard');
    }

    public function view(int $uuid) {

    }

    public function create(Request $request) {
        $validated = $request->validate([
                                            'name' => ['required', 'string', 'max:255'],
                                        ]);

        Meter::create([
                          'uuid'    => Str::uuid()->toString(),
                          'user_id' => auth()->user()->id,
                          'name'    => $validated['name'],
                          'keyword' => '',
                      ]);

        return back()->with('alert-success', __('meter.created'));
    }
}
