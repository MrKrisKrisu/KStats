@extends('layout.app')

@section('title')Deine Top Tracks @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Zeitraum wählen</h5>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-danger" href="{{route('spotify.topTracks', ['term' => 'long_term'])}}">
                            {{__('spotify.last_years')}}
                        </a>
                        <a class="btn btn-warning" href="{{route('spotify.topTracks', ['term' => 'medium_term'])}}">
                            {{__('spotify.last_months')}}
                        </a>
                        <a class="btn btn-success" href="{{route('spotify.topTracks', ['term' => 'short_term'])}}">
                            {{__('spotify.last_weeks')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="ui table unstackable">
                        <thead>
                        <tr>
                            <th>{{__('spotify.place')}}</th>
                            <th></th>
                            <th>{{__('spotify.track')}}</th>
                            <th>{{__('spotify.preview')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($top_tracks->items as $track)
                            <tr>
                                <td>#{{$loop->index + 1}}</td>
                                <td>
                                    @isset($track->album->images[0]->url)
                                        <img src="{{$track->album->images[0]->url}}" class="spotify-cover"
                                             style="max-width: 100px;"/>
                                    @endisset
                                </td>
                                <td>
                                    <b>{{$track->name}}</b><br/>
                                    <small>
                                        @foreach($track->artists as $artist)
                                            @if($loop->first)von @endif
                                            {{$artist->name}}
                                            @if(!$loop->last) und @endif
                                        @endforeach
                                    </small>

                                    @if($track->popularity > 80)
                                        <span class="badge badge-primary">{{__('spotify.popular_track')}}</span>
                                    @endif
                                </td>
                                <td>
                                    <audio controls>
                                        <source src="{{$track->preview_url}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection