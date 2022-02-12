@extends('layout.app')

@section('title', __('spotify.title.mood_o_meter'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-2">
                <div class="card-body">
                    <i class="far fa-sad-cry fa-3x text-danger float-start"></i>
                    <i class="far fa-smile fa-3x text-success float-end"></i>
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