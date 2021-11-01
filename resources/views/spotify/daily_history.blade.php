@extends('layout.app')

@section('title', __('stats.daily', ['date' => $date->format('d.m.Y')]))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{route('spotify.history', ['date' => $date->clone()->addDays(-1)->toDateString()])}}"
                       class="btn btn-sm btn-primary float-start">
                        <i class="fas fa-arrow-left"></i> {{__('general.pagination.previous_day')}}
                    </a>
                    @if($date->isBefore(\Carbon\Carbon::today()))
                        <a href="{{route('spotify.history', ['date' => $date->clone()->addDays(1)->toDateString()])}}"
                           class="btn btn-sm btn-primary float-end">
                            {{__('general.pagination.next_day')}} <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <span class="color-primary" style="font-size: 30px;" data-bs-toggle="tooltip" data-placement="top"
                          title="{{__('percent-of-day', ['percent' => round($minTotal / 1440 * 100)])}}">
                        <span>{{$minTotal}}</span><small>min</small>
                    </span><br/>
                    <span class="text-muted">{{__('spotify.listened-total')}}</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <span class="color-primary" style="font-size: 30px;">
                        <span>{{$tracksDistinct}}</span><small>x</small>
                    </span><br/>
                    <span class="text-muted">{{__('spotify.different-tracks')}}</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <span class="color-primary" style="font-size: 30px;">
                        <span>{{$sessions}}</span><small>x</small>
                    </span><br/>
                    <span class="text-muted">{{__('spotify.sessions')}}</span>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $playActivity)
                                    <tr>
                                        <td>
                                            {{$playActivity->timestamp_start->format('H:i')}}
                                            -
                                            {{$playActivity->timestamp_end->format('H:i')}}
                                        </td>
                                        <td>
                                            <a href="{{route('spotify.track' ,['id' => $playActivity->track->id])}}">
                                                {{$playActivity->track->name}}
                                            </a>
                                        </td>
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
