@extends('layout.app')

@section('title', __('general.menu.dashboard'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @isset($lastSpotifyTrack)
                        <small class="text-muted float-end">{{__('played')}} {{$lastSpotifyTrack->timestamp_start->diffForHumans()}}</small>
                    @endisset
                    <h5 class="card-title">{{__('spotify.title.last_heared')}}</h5>
                    @isset($lastSpotifyTrack)
                        @include('spotify.components.track', ['track' => $lastSpotifyTrack->track])
                    @else
                        <p class="text-danger">{{__('spotify.no-data')}}</p>
                    @endisset
                </div>
            </div>
        </div>
        <div class="col-md-6">
            @include('settings.cards.third-party')
        </div>
    </div>
@endsection
