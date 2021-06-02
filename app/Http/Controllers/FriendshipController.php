<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Friendship;
use App\Models\FriendshipRequest;
use App\Models\User;

class FriendshipController extends Controller {

    public function renderFriendshipPage(): Renderable {

        if(!auth()->user()->visible) {
            return view('social.friendship.not-visible');
        }

        return view('social.friendship.overview');
    }

    public function activateModule(): RedirectResponse {
        auth()->user()->update(['visible' => true]);
        return back();
    }

    public function cancelFriendship(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'friend_id' => ['required', Rule::in(auth()->user()->friends->pluck('id'))]
                                        ]);

        Friendship::where('user_id', auth()->user()->id)->where('friend_id', $validated['friend_id'])->delete();
        Friendship::where('friend_id', auth()->user()->id)->where('user_id', $validated['friend_id'])->delete();

        return back();
    }

    public function requestFriendship(Request $request): RedirectResponse {
        $validated = $request->validate([
                                            'username' => [
                                                'required',
                                                'exists:users,username',
                                                Rule::notIn([auth()->user()->username]),
                                                Rule::notIn(auth()->user()->friends->pluck('username'))
                                            ]
                                        ]);

        $requester = auth()->user();
        $user      = User::where('username', $validated['username'])->firstOrFail();

        if(!$user->visible) {
            return back()->with('alert-danger', 'Der angegebene Benutzer hat die Freundschaftsfunktion nicht aktiviert.');
        }

        $otherRequest = FriendshipRequest::where('requester_id', $user->id)->where('user_id', $requester->id)->first();

        if($otherRequest != null) {
            $otherRequest->delete();
            Friendship::create(['user_id' => $requester->id, 'friend_id' => $user->id]);
            Friendship::create(['user_id' => $user->id, 'friend_id' => $requester->id]);
            return back()->with('alert-success', 'Da der User dir auch eine Anfrage geschickt hat wurde die Freundschaft bestätigt.');
        }

        FriendshipRequest::create([
                                      'requester_id' => $requester->id,
                                      'user_id'      => $user->id
                                  ]);


        return back()->with('alert-success', 'Die Anfrage wurde gespeichert. Um diese zu bestätigen muss der User deinen Usernamen ebenfalls anfragen.');
    }
}
