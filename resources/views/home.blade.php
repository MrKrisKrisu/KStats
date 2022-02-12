@extends('layout.app')

@section('title', __('general.menu.dashboard'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    @isset($lastSpotifyTrack)
                        <small class="text-muted float-end">{{__('played')}} {{$lastSpotifyTrack->timestamp_start->diffForHumans()}}</small>
                    @endisset
                    <h2 class="fs-5">
                        <i class="fas fa-headphones-alt"></i>
                        {{__('spotify.title.last_heared')}}
                    </h2>
                    @isset($lastSpotifyTrack)
                        @include('spotify.components.track', ['track' => $lastSpotifyTrack->track])

                        <a href="{{route('spotify.history')}}" class="btn btn-sm btn-outline-secondary float-end">
                            <i class="fas fa-history"></i>
                            {{__('spotify.show_history')}}
                        </a>
                    @else
                        <p class="text-danger">{{__('spotify.no-data')}}</p>
                    @endisset
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-body">
                    <h2 class="fs-5"><i class="fas fa-language"></i> {{__('languages')}}</h2>
                    <p>
                        {{__('languages.other1')}}
                        {{__('languages.support.de-en')}}
                        {{__('languages.support')}}
                    </p>

                    <a href="https://weblate.k118.de/engage/kstats/" target="weblate"
                       class="btn btn-sm btn-primary float-end">
                        <i class="fas fa-mouse-pointer"></i> {{__('languages.help')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            @include('settings.cards.third-party')
        </div>
    </div>
@endsection
