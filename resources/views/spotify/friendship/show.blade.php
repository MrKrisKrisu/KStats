@extends('layout.app')

@section('title', __('friendship-playlist-friend', ['friendName' => $friend->username]))

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    @if($tracks->count() == 0)
                        <span class="text-danger">{{__('no-common-tracks')}}</span>
                    @else
                        <table class="table">
                            @foreach($tracks as $track)
                                <tr>
                                    <td>
                                        @isset($track->album->imageUrl)
                                            <a href="{{route('spotify.track', $track->id)}}">
                                                <img src="{{$track->album->imageUrl}}" class="spotify-cover"
                                                     style="max-width: 110px;"/>
                                            </a>
                                        @endisset
                                    </td>
                                    <td>
                                        <a href="{{route('spotify.track', $track->id)}}">{{$track->name}}</a><br/>
                                        <small>
                                            @foreach($track->artists as $artist)
                                                @if($loop->first){{__('general.from')}} @endif
                                                {{$artist->name}}
                                                @if(!$loop->last) {{__('general.and')}} @endif
                                            @endforeach
                                        </small>
                                        @isset($track->preview_url)
                                            <hr/>
                                            <audio controls>
                                                <source src="{{$track->preview_url}}" type="audio/mpeg">
                                                {{__('no-browser-support')}}
                                            </audio>
                                        @endisset
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-success btn-lg" target="spotify"
                       href="https://open.spotify.com/playlist/{{$playlistId}}">
                        <i class="fab fa-spotify"></i> {{__('open-in-spotify')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

