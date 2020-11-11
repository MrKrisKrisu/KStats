@extends('layout.app')

@section('title')Deine Top Tracks zwischen {{$from->format('d.m.Y')}} und {{$to->format('d.m.Y')}} @endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Zeitraum wählen</h2>
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
                        Letztes Jahr ({{\Carbon\Carbon::now()->subYear()->format('Y')}})
                    </a>
                    <a href="?from={{\Carbon\Carbon::now()->format('Y')}}-01-01" class="btn btn-primary">
                        Dieses Jahr ({{\Carbon\Carbon::now()->format('Y')}})
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Eigenen Zeitraum wählen</h2>
                    <form method="GET">
                        <input type="hidden" name="page" value="1"/>
                        <div class="form-group">
                            <label>Von (Datum)</label>
                            <input type="date" name="from" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Bis (Datum)</label>
                            <input type="date" name="to" class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Öffnen</button>
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
                            <h2>
                                Platz {{$loop->index + 1 + ($top_tracks->perPage() * ($top_tracks->currentPage() - 1))}}</h2>
                            @include('spotify.components.track', ['track' => $activity->track, 'minutes' => $activity->minutes])
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
                        <p class="text-danger font-weight-bold">Es sind keine TopTracks in diesem Zeitraum
                            vorhanden.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection