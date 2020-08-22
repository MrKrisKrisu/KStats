@extends('layout.app')

@section('title')No data, yet! @endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('general.error.no_data')}}</h5>
                    <p>{{__('settings.connected', ['service' => 'Spotify'])}} {{__('spotify.play_music')}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
