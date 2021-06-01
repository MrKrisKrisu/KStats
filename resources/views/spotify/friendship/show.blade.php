@extends('layout.app')

@section('title', 'Freundschaftsplaylist von dir und ' . $friend->username)

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    @if($tracks->count() == 0)
                        <span class="text-danger">Ihr habt keine gemeinsamen Lieblingstracks.</span>
                        <span>Vielleicht entdeckt ihr ja <a href="{{route('spotify.explore')}}">hier</a> welche?</span>
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
                                                Your browser does not support the audio element.
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
                    <a class="btn btn-success btn-lg btn-block" target="spotify"
                       href="https://open.spotify.com/playlist/{{$playlistId}}">
                        <i class="fab fa-spotify"></i> Playlist in Spotify öffnen
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

