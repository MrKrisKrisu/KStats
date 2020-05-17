@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    <div class="row">
                        <div class="col">
                                <span style="color: #38A3A6; font-size: 50px;"
                                      id="lieblingsjahr">{{$favouriteYear}}</span><br>
                            <small><b>Dein Lieblingsjahr</b></small>
                        </div>
                        <div class="col">
                            <span style="color: #38A3A6; font-size: 50px;"
                                  id="bpm">{{$bpm}}<small>BPM</small></span><br>
                            <small><b>Deine Lieblingsgeschwindigkeit</b></small>
                        </div>
                        <div class="col">
                                <span style="color: #38A3A6; font-size: 50px;"
                                      id="trackcount">{{$uniqueSongs}}</span><br>
                            <small><b>Versch. Lieder gehört</b></small>
                        </div>
                        <div class="col">
                        <span style="color: #38A3A6; font-size: 50px;"
                              id="avgPerSession">{{$avgSession}}<small>min</small>
        <i class="mdi mdi-trending-down" style="font-size: 25px; color: rgb(255, 99, 132)"></i>
        </span><br>
                            <small><b>hörst du im Schnitt am Stück</b></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Zuletzt angehört</h5>
                    <div class="row">
                        @isset($lastPlayActivity->track->album->imageUrl)
                            <div class="col-md-4">
                                <img src="{{$lastPlayActivity->track->album->imageUrl}}"
                                     class="cover"/>
                            </div>
                        @endisset
                        <div class="col">
                            <b>{{$lastPlayActivity->track->name ?? "Unknown Song"}}</b><br/>
                            @isset($lastPlayActivity->track->artists[0]->name)
                                <small>von <i>{{$lastPlayActivity->track->artists[0]->name}}</i></small>
                            @endisset
                            @isset($lastPlayActivity->track->preview_url)
                                <hr/>
                                <audio controls="">
                                    <source src="{{$lastPlayActivity->track->preview_url}}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @endisset

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gehörte Minuten</h5>
                    <table class="ui table">
                        <tbody>
                        <tr>
                            <td><b>gesamt</b></td>
                            <td>{{ $hearedMinutesTotal }} {{ _('Minutes')  }}</td>
                        </tr>
                        <tr>
                            <td><b>letzte 30 Tage</b></td>
                            <td>{{ $hearedMinutes30d }} {{ _('Minutes')  }}</td>
                        </tr>
                        <tr>
                            <td><b>letzte 7 Tage</b></td>
                            <td>{{ $hearedMinutes7d }} {{ _('Minutes')  }}</td>
                        </tr>
                        <tr>
                            <td><b>letzte 24 Stunden</b></td>
                            <td>{{ $hearedMinutes1d }} {{ _('Minutes')  }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Deine Top Tracks [gesamt]</h5>
                    @foreach($topTracksTotal as $ttList)
                        <div class="row">
                            @isset($ttList->track->album->imageUrl)
                                <div class="col-md-4">
                                    <img src="{{$ttList->track->album->imageUrl}}" class="cover"/>
                                </div>
                            @endisset
                            <div class="col">
                                <b>{{$ttList->track->name}}</b><br>
                                @isset($ttList->track->artists[0])
                                    <small>von <i>{{$ttList->track->artists[0]->name}}</i></small><br/>
                                @endisset
                                <small>{{$ttList->minutes}} Minuten gehört</small>
                                @isset($ttList->track->preview_url)
                                    <audio controls="">
                                        <source src="{{$ttList->track->preview_url}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endisset
                            </div>
                        </div>
                        <hr/>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Deine Top Tracks [letzte 30 Tage]</h5>
                    @foreach($topTracks30d as $ttList)
                        <div class="row">
                            @isset($ttList->track->album->imageUrl)
                                <div class="col-md-4">
                                    <img src="{{$ttList->track->album->imageUrl}}" class="cover"/>
                                </div>
                            @endisset
                            <div class="col">
                                <b>{{$ttList->track->name}}</b><br>
                                @isset($ttList->track->artists[0])
                                    <small>von <i>{{$ttList->track->artists[0]->name}}</i></small><br/>
                                @endisset
                                <small>{{$ttList->minutes}} Minuten gehört</small>
                                @isset($ttList->track->preview_url)
                                    <audio controls="">
                                        <source src="{{$ttList->track->preview_url}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endisset
                            </div>
                        </div>
                        <hr/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gehörte Minuten nach Woche</h5>
                    <canvas id="chart_hearedByWeek"></canvas>

                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var chart_hearedByWeek = document.getElementById('chart_hearedByWeek').getContext('2d');
                        window.chart_hearedByWeek = new Chart(chart_hearedByWeek, {
                            type: 'line',
                            data: {
                                datasets: [{
                                    backgroundColor: ["#38a2a6"],
                                    data: [
                                        @foreach($chartData_hearedByWeek as $weekData)
                                        {{$weekData->minutes}},
                                        @endforeach
                                    ]
                                }],
                                labels: [
                                    @foreach($chartData_hearedByWeek as $weekData)
                                        'KW {{$weekData->week}} / {{$weekData->year}}',
                                    @endforeach
                                ]
                            },
                            options: {
                                responsive: true,
                                legend: {
                                    display: false,
                                },
                                animation: {
                                    animateScale: true,
                                    animateRotate: true
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
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top Künstler [gesamt]</h5>
                    <table class="ui table unstackable">
                        <thead>
                        <tr>
                            <th>Platz</th>
                            <th>Künstler</th>
                            <th>Hörzeit</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($i = 1)
                        @foreach($topArtistsTotal as $artist)
                            <tr>
                                <td>#{{$i++}}</td>
                                <td><b>{{$artist->name}}</b></td>
                                <td>{{$artist->minutes}} min</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top Künstler [letzte 30 Tage]</h5>
                    <table class="ui table unstackable">
                        <thead>
                        <tr>
                            <th>Platz</th>
                            <th>Künstler</th>
                            <th>Hörzeit</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($i = 1)
                        @foreach($topArtists30d as $artist)
                            <tr>
                                <td>#{{$i++}}</td>
                                <td><b>{{$artist->name}}</b></td>
                                <td>{{$artist->minutes}} min</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top Künstler [letzte 7 Tage]</h5>
                    <table class="ui table unstackable">
                        <thead>
                        <tr>
                            <th>Platz</th>
                            <th>Künstler</th>
                            <th>Hörzeit</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($i = 1)
                        @foreach($topArtists7d as $artist)
                            <tr>
                                <td>#{{$i++}}</td>
                                <td><b>{{$artist->name}}</b></td>
                                <td>{{$artist->minutes}} min</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card" style="margin-top: 10px;">
                <div class="card-body">
                    <h5 class="card-title">Gehörte Minuten nach Wochentag</h5>
                    <canvas id="chart_listenedByWeekday"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var chart_listenedByWeekday = document.getElementById('chart_listenedByWeekday').getContext('2d');
                        window.chart_listenedByWeekday = new Chart(chart_listenedByWeekday, {
                            type: 'bar',
                            data: {
                                labels: [
                                    @foreach($chartData_hearedByWeekday as $weekData)
                                        '{{\App\Http\Controllers\SpotifyController::getWeekdayName($weekData->weekday)}}',
                                    @endforeach
                                ],
                                datasets: [{
                                    label: 'Gehörte Minuten',
                                    backgroundColor: '#38a3a6',
                                    borderWidth: 1,
                                    data: [@foreach($chartData_hearedByWeekday as $weekData)
                                        {{$weekData->minutes}},
                                        @endforeach
                                    ]
                                }]

                            },
                            options: {
                                responsive: true,
                                legend: {
                                    display: false,
                                },
                                animation: {
                                    animateScale: true,
                                    animateRotate: true
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            min: 0
                                        }
                                    }]
                                }
                            }
                        });
                    });
                </script>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card" style="margin-top: 10px;">
                <div class="card-body">
                    <h5 class="card-title">Gehörte Minuten nach Uhrzeit</h5>
                    <canvas id="chart_listenedByHour"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var chart_listenedByHour = document.getElementById('chart_listenedByHour').getContext('2d');
                        window.chart_listenedByHour = new Chart(chart_listenedByHour, {
                            type: 'bar',
                            data: {
                                labels: [
                                    @foreach($chartData_hearedByHour as $weekData)
                                        '{{$weekData->hour}} Uhr',
                                    @endforeach
                                ],
                                datasets: [{
                                    label: 'Gehörte Minuten',
                                    backgroundColor: '#38a3a6',
                                    borderWidth: 1,
                                    data: [
                                        @foreach($chartData_hearedByHour as $weekData)
                                        {{$weekData->minutes}},
                                        @endforeach
                                    ]
                                }]

                            },
                            options: {
                                responsive: true,
                                legend: {
                                    display: false,
                                },
                                animation: {
                                    animateScale: true,
                                    animateRotate: true
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            min: 0
                                        }
                                    }]
                                }
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection