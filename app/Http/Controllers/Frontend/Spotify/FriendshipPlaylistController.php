<?php

namespace App\Http\Controllers\Frontend\Spotify;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Backend\Spotify\FriendshipPlaylistController as FriendshipPlaylistBackend;
use Illuminate\Http\RedirectResponse;
use App\SpotifyFriendshipPlaylist;

class FriendshipPlaylistController extends Controller {

    public function renderFriendshipPlaylists(): View {
        return view('spotify.friendship.overview');
    }

    public function createFriendshipPlaylist(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'friend_id' => [
                                                'required',
                                                'exists:users,id',
                                                Rule::in(auth()->user()->friends->pluck('id'))
                                            ]
                                        ]);

        $friend = User::find($validated['friend_id']);

        FriendshipPlaylistBackend::getFriendshipPlaylist(
            user: auth()->user(),
            friend: $friend,
            createIfDoesntExist: true
        );

        return redirect()->route('spotify.friendship-playlists.show', ['friendId' => $friend->id]);
    }

    public function renderList(int $friendId): View|RedirectResponse {
        $friend = User::findOrFail($friendId);

        if(!auth()->user()->friends->contains('id', $friend->id)) {
            abort(404);
        }

        $playlistId = SpotifyFriendshipPlaylist::where('user_id', auth()->user()->id)
                                               ->where('friend_id', $friend->id)
                                               ->first()?->playlist_id;

        if($playlistId == null) {
            return back()->with('alert-danger', 'Ihr besitzt aktuell keine gemeinsame Playlist.');
        }

        return view('spotify.friendship.show', [
            'friend'     => $friend,
            'tracks'     => FriendshipPlaylistBackend::getCommonTracks(auth()->user(), $friend),
            'playlistId' => $playlistId
        ]);
    }

}
