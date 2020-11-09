<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('home');
    }

    public static function getCurrentGitHash(): string
    {
        try {
            $gitBasePath = base_path() . '/.git';

            $gitStr = file_get_contents($gitBasePath . '/HEAD');
            return rtrim(preg_replace("/(.*?\/){2}/", '', $gitStr));
        } catch (\Exception $e) {
            report($e);
            return 'unknown';
        }
    }
}
