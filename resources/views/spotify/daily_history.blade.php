@extends('layout.app')

@section('title') Tagesstatistik vom {{$date->format('d.m.Y')}} @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{route('spotify.history', ['date' => $date->clone()->addDays(-1)->toDateString()])}}"
                       class="btn btn-sm btn-primary float-left">
                        <i class="fas fa-arrow-left"></i> {{__('general.pagination.previous_day')}}
                    </a>
                    @if($date->isBefore(\Carbon\Carbon::today()))
                        <a href="{{route('spotify.history', ['date' => $date->clone()->addDays(1)->toDateString()])}}"
                           class="btn btn-sm btn-primary float-right">
                            {{__('general.pagination.next_day')}} <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <span class="color-primary" style="font-size: 30px;" data-toggle="tooltip" data-placement="top"
                          title="Das sind etwa {{round($minTotal / 1440 * 100)}}% des ganzen Tages!">
                        <span>{{$minTotal}}</span><small>min</small>
                    </span><br/>
                    <span class="text-muted">hast du insgesamt gehört</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <span class="color-primary" style="font-size: 30px;">
                        <span>{{$tracksDistinct}}</span><small>x</small>
                    </span><br/>
                    <span class="text-muted">verschiedene Tracks</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <span class="color-primary" style="font-size: 30px;">
                        <span>{{$sessions}}</span><small>x</small>
                    </span><br/>
                    <span class="text-muted">Musik-Sessions</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if(count($history) == 0)
                        <p class="text-danger">{{__('general.error.no_data_day')}}</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{__('spotify.time_period')}}</th>
                                    <th>{{__('spotify.track')}}</th>
                                    <th>{{__('spotify.device')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $playActivity)
                                    <tr>
                                        <td>{{$playActivity->timestamp_start->format('H:i')}}
                                            - {{\Carbon\Carbon::parse($playActivity->played_until)->format('H:i')}}</td>
                                        <td>
                                            <a href="{{route('spotify.track' ,['id' => $playActivity->track->id])}}">{{$playActivity->track->name}}</a>
                                        </td>
                                        <td>{{$playActivity->device->name}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$history->onEachSide(1)->links()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
