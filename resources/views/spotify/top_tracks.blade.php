@extends('layout.app')

@section('title')Deine Top Tracks @endsection

@section('content')
    <div class="row">
        @foreach($top_tracks as $activity)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2>
                            Platz {{$loop->index + 1 + ($top_tracks->perPage() * ($top_tracks->currentPage() - 1))}}</h2>
                        @include('spotify.components.track', ['track' => $activity->track, 'minutes' => $activity->minutes])
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-12">
            {{$top_tracks->withQueryString()->links()}}
        </div>
    </div>
@endsection