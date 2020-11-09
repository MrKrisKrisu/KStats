@extends('layout.app')

@section('title') {{$artist->name}} @endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h2 style="font-size: 30px;">{{number_format($artist->tracks->count(), 0, ',','.')}} Tracks</h2>
                    <span class="text-muted">Anzahl bei KStats erfasster Tracks</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($artist->tracks->avg('valence') > 0.5)
                        <h2 style="font-size: 50px;"><i class="far fa-smile text-success"></i></h2>
                        <span class="text-muted">Die meisten Tracks von {{$artist->name}} machen eher eine gute Stimmung.</span>
                    @elseif($artist->tracks->avg('valence') > 0.3)
                        <h2 style="font-size: 50px;">
                            <i class="far fa-smile text-success"></i>
                            <i class="far fa-sad-tear text-danger"></i>
                        </h2>
                        <span class="text-muted">{{$artist->name}} hat für jede Stimmung den passenden Track.</span>
                    @else
                        <h2 style="font-size: 50px;"><i class="far fa-sad-tear text-danger"></i></h2>
                        <span class="text-muted">Die meisten Tracks von {{$artist->name}} machen eher eine traurige oder aggressive Stimmung.</span>
                    @endif
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <span class="text-danger">Hinweis: Es fließen nur Tracks in die Statistik ein, welche von einem KStats-User
                        mindestens einmal gehört wurden.</span>
                </div>
            </div>
        </div>
    </div>
@endsection

