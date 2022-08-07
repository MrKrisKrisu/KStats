<?php

namespace App\Http\Controllers\Frontend\SharedLink;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\SharedLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SharedLinkController extends Controller {

    public function showSharedLinks(): View {
        return view('sharedlink.settings', [
            'sharedLinks' => SharedLink::where('user_id', auth()->id())->get(),
        ]);
    }

    public function createSharedLink(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'spotify_tracks' => ['required', 'numeric', 'gte:3', 'lte:100'],
                                            'spotify_days'   => ['required', 'numeric', 'gte:14', 'lte:3560'],
                                        ]);

        $validated['user_id']  = auth()->id();
        $validated['share_id'] = Str::uuid();

        $sharedLink = SharedLink::create($validated);

        return back()->with('alert-success', __('shared-links.created') . ' ' . $sharedLink->url);
    }

    public function deleteSharedLink(Request $request): RedirectResponse {
        $validated  = $request->validate([
                                             'id' => ['required', 'exists:shared_links,id'],
                                         ]);
        $sharedLink = SharedLink::find($validated['id']);
        if($sharedLink->user_id !== auth()->user()->id) {
            abort(403);
        }
        $sharedLink->delete();
        return back()->with('alert-success', __('shared-links.deleted'));
    }

    public function show(string $username, string $shareId): View {
        $user       = User::where('username', $username)->firstOrFail();
        $sharedLink = SharedLink::where('user_id', $user->id)->where('share_id', $shareId)->firstOrFail();

        $topTracks = $user->spotifyActivity()
                          ->with(['track', 'track.album', 'track.artists'])
                          ->where('timestamp_start', '>', Carbon::now()->subDays($sharedLink->spotify_days))
                          ->groupBy('track_id')
                          ->select('track_id', DB::raw('SUM(duration) / 60 as minutes'))
                          ->orderBy('minutes', 'DESC')
                          ->limit(min($sharedLink->spotify_tracks, 100))
                          ->get();
        return view('sharedlink.public', [
            'user'       => $user,
            'sharedLink' => $sharedLink,
            'topTracks'  => $topTracks,
        ]);
    }

}
