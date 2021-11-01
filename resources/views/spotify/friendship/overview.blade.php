@extends('layout.app')

@section('title', __('spotify.title.friendship-playlists'))

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    @if(auth()->user()->friends->count() == 0)
                        <span class="text-danger">{{__('no-friends')}}</span>
                        <hr/>
                        <a href="{{route('friendships')}}">{{__('add-friends')}}</a>
                    @else
                        <table class="table">
                            @foreach(auth()->user()->friends as $friend)
                                <tr>
                                    <td>{{$friend->username}}</td>
                                    @if(auth()->user()->spotifyFriendshipPlaylists->contains('friend_id', $friend->id))
                                        <td>
                                            <a class="btn btn-sm btn-primary"
                                               href="{{route('spotify.friendship-playlists.show', ['friendId' => $friend->id])}}">
                                                <i class="far fa-eye"></i> {{__('show-playlist')}}
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-success btn-sm" target="spotify"
                                               href="https://open.spotify.com/playlist/{{auth()->user()->spotifyFriendshipPlaylists->where('friend_id', $friend->id)->first()?->playlist_id}}">
                                                <i class="fab fa-spotify"></i> {{__('open-in-spotify')}}
                                            </a>
                                        </td>
                                    @else
                                        <td>
                                            <a class="btn btn-sm btn-success"
                                               data-bs-toggle="modal" data-bs-target="#createModal"
                                               onclick="$('#confirm-create-btn').attr('form', 'create-{{$friend->id}}')">
                                                <i class="fas fa-plus-square"></i> {{__('create-playlist')}}
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
                    <h5 class="modal-title">{{__('create-playlist')}}</h5>
                    <button type="button" class="close"data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('create-playlist.disclaimer')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="confirm-create-btn"
                            onclick="$('#createModal').modal('hide'); showLoadPopup();">
                        {{__('create-playlist')}}
                    </button>
                    <button type="button" class="btn btn-secondary"data-bs-dismiss="modal">{{__('abort')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

