@extends('layout.app')

@section('title')Gehörte Lieder am {{$date->format('d.m.Y')}} @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{route('spotify.history', ['date' => $date->clone()->addDays(-1)->toDateString()])}}"
                       class="btn btn-sm btn-primary float-left">
                        <i class="fas fa-arrow-left"></i> Vorheriger Tag
                    </a>
                    @if($date->isBefore(\Carbon\Carbon::today()))
                        <a href="{{route('spotify.history', ['date' => $date->clone()->addDays(1)->toDateString()])}}"
                           class="btn btn-sm btn-primary float-right">
                            Nächster Tag <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if(count($history) == 0)
                        <p class="text-danger">Es sind keine Aufzeichnungen an diesem Tag vorhanden.</p>
                    @else
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Uhrzeit</th>
                                <th>Track</th>
                                <th>Gerät</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($history as $playActivity)
                                <tr>
                                    <td>{{$playActivity->timestamp_start->format('H:i')}}</td>
                                    <td>
                                        <a href="{{route('spotify.track' ,['id' => $playActivity->track->id])}}">{{$playActivity->track->name}}</a>
                                    </td>
                                    <td>{{$playActivity->device->name}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
