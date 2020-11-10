@extends('layout.app')

@section('title') Mood-O-Meter @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <i class="far fa-sad-cry fa-3x text-danger float-left"></i>
                    <i class="far fa-smile fa-3x text-success float-right"></i>
                    <div class="clearfix"></div>
                    <hr/>
                    @for($date = \Carbon\Carbon::today(); $date->diffInDays() < 30; $date->subDay())
                        @include('spotify.mood-o-meter.mood-bar', [
                            'date' => $date,
                            'valence' => $daily[$date->toDateString()] ?? -1
                        ])
                    @endfor
                </div>
            </div>
        </div>
    </div>
@endsection