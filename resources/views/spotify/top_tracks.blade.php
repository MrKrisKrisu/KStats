@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Such 's dir aus!</div>
                <div class="card-body">
                    <p>Welche Statistiken möchtest du sehen? Wir haben drei mögliche Zeiträume!</p>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-danger" href="{{route('spotify.topTracks', ['term' => 'long_term'])}}">die letzten Jahre</a>
                        <a class="btn btn-warning" href="{{route('spotify.topTracks', ['term' => 'medium_term'])}}">letzte 6 Monate</a>
                        <a class="btn btn-success" href="{{route('spotify.topTracks', ['term' => 'short_term'])}}">letzte 4 Wochen</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Top Tracks</div>
                <div class="card-body">
                    <table class="ui table unstackable">
                        <thead>
                        <tr>
                            <th>Platz</th>
                            <th>Titel</th>
                            <th>Preview</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($i = 1)
                        @foreach($top_tracks->items as $track)
                            <tr>
                                <td>#{{$i++}}</td>
                                <td><b>{{$track->name}}</b></td>
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