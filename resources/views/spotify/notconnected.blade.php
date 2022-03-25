@extends('layout.app')

@section('title', __('settings.connect'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('settings.connect')}}</h5>
                    <p>{{__('settings.not_connected', ['service' => 'Spotify'])}}</p>

                    <a href="{{route('redirectProvider', 'spotify')}}"
                       class="btn btn-success">{{__('settings.connect')}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
