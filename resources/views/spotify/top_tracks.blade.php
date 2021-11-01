@extends('layout.app')

@section('title', __('top-tracks-between', [
    'from' => $from->format('d.m.Y'),
    'until' => $to->format('d.m.Y')
]))

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>{{__('date-range')}}</h2>
                    <a href="?from={{\Carbon\Carbon::now()->subMonths(2)->firstOfMonth()->toDateString()}}&to={{\Carbon\Carbon::now()->subMonths(2)->lastOfMonth()->toDateString()}}"
                       class="btn btn-primary">
                        {{\Carbon\Carbon::now()->subMonths(2)->isoFormat('MMMM YYYY')}}
                    </a>
                    <a href="?from={{\Carbon\Carbon::now()->subMonth()->firstOfMonth()->toDateString()}}&to={{\Carbon\Carbon::now()->subMonth()->lastOfMonth()->toDateString()}}"
                       class="btn btn-primary">
                        {{\Carbon\Carbon::now()->subMonth()->isoFormat('MMMM YYYY')}}
                    </a>
                    <a href="?from={{\Carbon\Carbon::now()->firstOfMonth()->toDateString()}}" class="btn btn-primary">
                        {{\Carbon\Carbon::now()->isoFormat('MMMM YYYY')}}
                    </a>
                    <hr/>
                    <a href="?from={{\Carbon\Carbon::now()->subYear()->format('Y')}}-01-01&to={{\Carbon\Carbon::now()->subYear()->format('Y')}}-12-31"
                       class="btn btn-primary">
                        {{__('last-year')}} ({{\Carbon\Carbon::now()->subYear()->format('Y')}})
                    </a>
                    <a href="?from={{\Carbon\Carbon::now()->format('Y')}}-01-01" class="btn btn-primary">
                        {{__('this-year')}} ({{\Carbon\Carbon::now()->format('Y')}})
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>{{__('date-range')}}</h2>
                    <form method="GET">
                        <input type="hidden" name="page" value="1"/>
                        <div class="row">
                            <div class="col">
                                <div class="mb-2">
                                    <label>{{__('from')}}</label>
                                    <input type="date" name="from" class="form-control"
                                           value="{{$from->format('Y-m-d')}}"/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-2">
                                    <label>{{__('until')}}</label>
                                    <input type="date" name="to" class="form-control" value="{{$to->format('Y-m-d')}}"/>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('show')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    @if(count($top_tracks) > 0)
        <div class="row">
            <div class="col-md-12">
                {{$top_tracks->withQueryString()->links()}}
            </div>
            @foreach($top_tracks as $activity)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h2>{{__('spotify.rank', ['rank' => $loop->index + 1 + ($top_tracks->perPage() * ($top_tracks->currentPage() - 1))])}}</h2>
                            @include('spotify.components.track', ['track' => $activity->track, 'minutes' => round($activity->minutes)])
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-md-12">
                {{$top_tracks->withQueryString()->links()}}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-danger fs-bold">{{__('spotify.top-list.none')}}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection