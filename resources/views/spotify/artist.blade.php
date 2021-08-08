@extends('layout.app')

@section('title', $artist->name)

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h2 style="font-size: 30px;">{{__('spotify.tracks', ['count' => $artist->tracks->count()])}}</h2>
                    <span class="text-muted">{{__('spotify.tracks.count')}}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($artist->tracks->avg('valence') > 0.5)
                        <h2 style="font-size: 50px;"><i class="far fa-smile text-success"></i></h2>
                        <span class="text-muted">{{__('spotify.tracks.most-happy', ['artistName' => $artist->name])}}</span>
                    @elseif($artist->tracks->avg('valence') > 0.3)
                        <h2 style="font-size: 50px;">
                            <i class="far fa-smile text-success"></i>
                            <i class="far fa-sad-tear text-danger"></i>
                        </h2>
                        <span class="text-muted">{{__('spotify.tracks.most-neutral', ['artistName' => $artist->name])}}</span>
                    @else
                        <h2 style="font-size: 50px;"><i class="far fa-sad-tear text-danger"></i></h2>
                        <span class="text-muted">{{__('spotify.tracks.most-sad', ['artistName' => $artist->name])}}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>{{__('tracks')}}</h2>
                    <table class="table">
                        <tbody>
                            @foreach($artist->tracks->sortByDesc('popularity') as $track)
                                <tr>
                                    <td>
                                        <a href="{{route('spotify.track', ['id' => $track->id])}}">{{$track->name}}</a>
                                        <br/>
                                        @include('spotify.track-attributes')
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <span class="text-danger">{{__('spotify.stats.disclaimer')}}</span>
                </div>
            </div>
        </div>
    </div>
@endsection

