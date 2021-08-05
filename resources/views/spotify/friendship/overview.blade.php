@extends('layout.app')

@section('title', 'Freundschaftsplaylisten')

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    @if(auth()->user()->friends->count() == 0)
                        <span class="text-danger">Du hast keine Freunde.</span>
                        <hr/>
                        <a href="{{route('friendships')}}">Freunde hinzufügen</a>
                    @else
                        <table class="table">
                            @foreach(auth()->user()->friends as $friend)
                                <tr>
                                    <td>{{$friend->username}}</td>
                                    @if(auth()->user()->spotifyFriendshipPlaylists->contains('friend_id', $friend->id))
                                        <td>
                                            <a class="btn btn-sm btn-primary"
                                               href="{{route('spotify.friendship-playlists.show', ['friendId' => $friend->id])}}">
                                                <i class="far fa-eye"></i> Playlist anzeigen
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-success btn-sm" target="spotify"
                                               href="https://open.spotify.com/playlist/{{auth()->user()->spotifyFriendshipPlaylists->where('friend_id', $friend->id)->first()?->playlist_id}}">
                                                <i class="fab fa-spotify"></i> In Spotify öffnen
                                            </a>
                                        </td>
                                    @else
                                        <td>
                                            <a class="btn btn-sm btn-success"
                                               data-toggle="modal" data-target="#createModal"
                                               onclick="$('#confirm-create-btn').attr('form', 'create-{{$friend->id}}')">
                                                <i class="fas fa-plus-square"></i> Playlist erstellen
                                            </a>
                                        </td>
                                        <td></td>

                                        <form method="POST" id="create-{{$friend->id}}"
                                              action="{{route('spotify.friendship-playlists.create')}}">
                                            @csrf
                                            <input type="hidden" name="friend_id" value="{{$friend->id}}"/>
                                        </form>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="createModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Playlist erstellen?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Mit der Bestätigung werden die Lieblingstracks von euch beiden ermittelt und eine Playlist in
                        deinem Spotify Konto erstellt.</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="confirm-create-btn"
                            onclick="$('#createModal').modal('hide'); showLoadPopup();">
                        Ja, erstellen!
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

