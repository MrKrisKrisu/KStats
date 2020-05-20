@extends('layout.app')

@section('title')Not connected to Twitter @endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Connect your Twitter Account</h5>
                    <p>You need to connect your Twitter Account to KStats to see statistics.</p>

                    <a href="{{route('redirectProvider', 'twitter')}}" class="btn btn-success">Connect to Twitter</a>
                </div>
            </div>
        </div>
    </div>
@endsection
