@extends('layout.app')

@section('title') KStats - Übersicht @endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @isset($lastSpotifyTrack)
                        <small class="text-muted float-right">abgespielt {{$lastSpotifyTrack->timestamp_start->diffForHumans()}}</small>
                    @endisset
                    <h5 class="card-title">Dein zuletzt gehörter Track</h5>
                    @isset($lastSpotifyTrack)
                        @include('spotify.components.track', ['track' => $lastSpotifyTrack->track])
                    @else
                        <p class="text-danger">
                            Du hast bisher noch kein Lied auf Spotify gehört oder nutzt die KStats
                            Statistik nicht! :c
                        </p>
                    @endisset
                </div>
            </div>
        </div>
        <div class="col-md-6">
            @include('settings.cards.third-party')
        </div>
    </div>
@endsection
