@extends('layout.app')

@section('title')Not connected to Spotify @endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Connect your Spotify Account</h5>
                    <p>You need to connect your Spotify Account to KStats to see statistics.</p>

                    <a href="{{route('redirectProvider', 'spotify')}}" class="btn btn-success">Spotify Connect</a>
                </div>
            </div>
        </div>
    </div>
@endsection
