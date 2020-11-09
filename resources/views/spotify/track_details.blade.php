@extends('layout.app')

@section('title')
    {{$track->name}}
    @isset($track->artists[0])
        <small>{{__('general.from')}} <i>{{$track->artists[0]->name}}</i></small>
    @endisset
@endsection

@section('content')
    <div class="row">
        @isset($track->album->imageUrl)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <img src="{{$track->album->imageUrl}}" class="spotify-cover"/>
                    </div>
                </div>
            </div>
        @endisset
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h2>Vorschau</h2>
                    @isset($track->preview_url)
                        <audio controls="">
                            <source src="{{$track->preview_url}}" type="audio/mpeg">
                            Your browser does not support the audio element.';
                        </audio>
                    @else
                        <small class="text-muted">leider ist keine Vorschau vorhanden <i
                                    class="far fa-sad-cry"></i></small>
                    @endif
                    <hr/>
                    <a class="float-right btn btn-success" href="{{$track->spotify_link}}">
                        <i class="fab fa-spotify"></i> In Spotify öffnen
                    </a>
                </div>
            </div>
            @isset($track->album->release_date)
                <div class="card">
                    <div class="card-body text-center">
                        <span class="color-primary text-center" style="font-size: 35px;">
                            {{$track->album->release_date->isoFormat('MMMM YYYY')}}
                        </span><br/>
                        <small class="text-muted" style="font-size: 16px;">wurde der Track veröffentlicht</small>
                    </div>
                </div>
            @endisset
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h2>Interpreten</h2>
                    <ul class="list-group">
                        @foreach($track->artists as $artist)
                            <a href="{{route('spotify.artist', ['id' => $artist->id])}}"
                               class="list-group-item list-group-item-action">
                                {{$artist->name}}
                            </a>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('spotify.title.heared_minutes_by_day') }}</h5>
                    <canvas id="chart_minutes"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        new Chart(document.getElementById('chart_minutes').getContext('2d'), {
                            type: 'line',
                            data: {
                                datasets: [{
                                    backgroundColor: ["#38a2a6"],
                                    data: [
                                            @foreach($listening_days as $ld)
                                        {
                                            x: '{{$ld->date}}', y: {{$ld->minutes}}
                                        },
                                        @endforeach
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    display: false,
                                },
                                scales: {
                                    xAxes: [{
                                        type: 'time'
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            min: 0
                                        }
                                    }]
                                },
                                tooltips: {
                                    enabled: true,
                                    mode: 'single',
                                    callbacks: {
                                        label: function (tooltipItems, data) {
                                            return tooltipItems.yLabel + 'min';
                                        }
                                    }
                                },
                            },
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection

