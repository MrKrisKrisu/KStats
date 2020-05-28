@extends('layout.app')

@section('title')
    {{$track->name}}
    @isset($track->artists[0])
        <small>{{__('general.from')}} <i>{{$track->artists[0]->name}}</i></small>
    @endisset
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('spotify.title.heared_minutes_by_day') }}</h5>
                    <canvas id="chart_minutes"></canvas>

                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var ctx = document.getElementById('chart_minutes').getContext('2d');
                        var chart = new Chart(ctx, {
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

