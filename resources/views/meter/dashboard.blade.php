@extends('layout.app')

@section('title', __('general.meter'))

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('meter.name')}}}</th>
                                    <th>{{__('meter.last_reading')}}}</th>
                                    <th>{{__('meter.last_value')}}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(auth()->user()->meters as $meter)
                                    <tr>
                                        <td>{{$meter->name}}</td>
                                        <td>{{$meter->last_reading}}</td>
                                        <td>{{$meter->last_value}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
