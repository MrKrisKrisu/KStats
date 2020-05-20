@extends('layout.app')

@section('title')Spotify {{__('spotify.statistic')}} @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    <div class="row">
                        <div class="col">
                                <span class="color-highlight" style="font-size: 50px;"
                                      id="lieblingsjahr">{{$favouriteYear}}</span><br>
                            <small><b>{{__('spotify.title.favourite_year')}}</b></small>
                        </div>
                        <div class="col">
                            <span class="color-highlight" style="font-size: 50px;"
                                  id="bpm">{{$bpm}}<small>BPM</small></span><br>
                            <small><b>{{__('spotify.title.favourite_bpm')}}</b></small>
                        </div>
                        <div class="col">
                                <span class="color-highlight" style="font-size: 50px;"
                                      id="trackcount">{{$uniqueSongs}}</span><br>
                            <small><b>{{__('spotify.title.count_tracks')}}</b></small>
                        </div>
                        <div class="col">
                        <span class="color-highlight" style="font-size: 50px;"
                              id="avgPerSession">{{$avgSession}}<small>{{__('spotify.minutes.short')}}</small>
        <i class="mdi mdi-trending-down" style="font-size: 25px; color: rgb(255, 99, 132)"></i>
        </span><br>
                            <small><b>{{__('spotify.title.avg_session_length')}}</b></small>
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
                    <h5 class="card-title">{{__('spotify.title.last_heared')}}</h5>
                    <div class="row">
                        @isset($lastPlayActivity->track->album->imageUrl)
                            <div class="col-md-4">
                                <img src="{{$lastPlayActivity->track->album->imageUrl}}" class="spotify-cover"/>
                            </div>
                        @endisset
                        <div class="col">
                            <b>{{$lastPlayActivity->track->name ?? "Unknown Song"}}</b><br/>
                            @isset($lastPlayActivity->track->artists[0]->name)
                                <small>{{__('spotify.from')}} <i>{{$lastPlayActivity->track->artists[0]->name}}</i></small>
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
                    <h5 class="card-title">{{ __('spotify.heared_minutes') }}</h5>
                    <table class="ui table">
                        <tbody>
                        <tr>
                            <td><b>{{ __('spotify.total') }}</b></td>
                            <td>{{ $hearedMinutesTotal }} {{ $hearedMinutesTotal == 1 ?__('spotify.minutes.singular') :  __('spotify.minutes.plural') }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ __('spotify.last_days', ['days' => 30]) }}</b></td>
                            <td>{{ $hearedMinutes30d }} {{ $hearedMinutes30d == 1 ?__('spotify.minutes.singular') :  __('spotify.minutes.plural') }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ __('spotify.last_days', ['days' => 7]) }}</b></td>
                            <td>{{ $hearedMinutes7d }} {{ $hearedMinutes7d == 1 ?__('spotify.minutes.singular') :  __('spotify.minutes.plural') }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ __('spotify.last_hours', ['hours' => 24]) }}</b></td>
                            <td>{{ $hearedMinutes1d }} {{ $hearedMinutes1d == 1 ?__('spotify.minutes.singular') :  __('spotify.minutes.plural') }}</td>
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
                    <h5 class="card-title">{{ __('spotify.title.top_tracks') }} [{{ __('spotify.total') }}]</h5>
                    @foreach($topTracksTotal as $ttList)
                        <div class="row">
                            @isset($ttList->track->album->imageUrl)
                                <div class="col-md-4">
                                    <img src="{{$ttList->track->album->imageUrl}}" class="spotify-cover"/>
                                </div>
                            @endisset
                            <div class="col">
                                <b>{{$ttList->track->name}}</b><br>
                                @isset($ttList->track->artists[0])
                                    <small>{{__('spotify.from')}} <i>{{$ttList->track->artists[0]->name}}</i></small><br/>
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
                    <h5 class="card-title">{{ __('spotify.title.top_tracks') }}
                        [{{ __('spotify.last_days', ['days' => 30]) }}]</h5>
                    @foreach($topTracks30d as $ttList)
                        <div class="row">
                            @isset($ttList->track->album->imageUrl)
                                <div class="col-md-4">
                                    <img src="{{$ttList->track->album->imageUrl}}" class="spotify-cover"/>
                                </div>
                            @endisset
                            <div class="col">
                                <b>{{$ttList->track->name}}</b><br>
                                @isset($ttList->track->artists[0])
                                    <small>{{__('spotify.from')}} <i>{{$ttList->track->artists[0]->name}}</i></small><br/>
                                @endisset
                                <small>{{$ttList->minutes}} {{ __('spotify.minutes.heared') }}</small>
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
                    <h5 class="card-title">{{ __('spotify.title.heared_minutes_by_week') }}</h5>
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
                    <h5 class="card-title">{{ __('spotify.title.top_artists') }} [{{ __('spotify.total') }}]</h5>
                    <table class="ui table unstackable">
                        <thead>
                        <tr>
                            <th>{{ __('spotify.rank') }}</th>
                            <th>{{ __('spotify.artist') }}</th>
                            <th>{{ __('spotify.heared_minutes') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($i = 1)
                        @foreach($topArtistsTotal as $artist)
                            <tr>
                                <td>#{{$i++}}</td>
                                <td><b>{{$artist->name}}</b></td>
                                <td>{{$artist->minutes}} {{ __('spotify.minutes.short') }}</td>
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
                    <h5 class="card-title">{{ __('spotify.title.top_artists') }}
                        [{{ __('spotify.last_days', ['days' => 30]) }}]</h5>
                    <table class="ui table unstackable">
                        <thead>
                        <tr>
                            <th>{{ __('spotify.rank') }}</th>
                            <th>{{ __('spotify.artist') }}</th>
                            <th>{{ __('spotify.heared_minutes') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($i = 1)
                        @foreach($topArtists30d as $artist)
                            <tr>
                                <td>#{{$i++}}</td>
                                <td><b>{{$artist->name}}</b></td>
                                <td>{{$artist->minutes}} {{ __('spotify.minutes.short') }}</td>
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
                    <h5 class="card-title">{{ __('spotify.title.top_artists') }}
                        [{{ __('spotify.last_days', ['days' => 7]) }}]</h5>
                    <table class="ui table unstackable">
                        <thead>
                        <tr>
                            <th>{{ __('spotify.rank') }}</th>
                            <th>{{ __('spotify.artist') }}</th>
                            <th>{{ __('spotify.heared_minutes') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($i = 1)
                        @foreach($topArtists7d as $artist)
                            <tr>
                                <td>#{{$i++}}</td>
                                <td><b>{{$artist->name}}</b></td>
                                <td>{{$artist->minutes}} {{ __('spotify.minutes.short') }}</td>
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
                    <h5 class="card-title">{{ __('spotify.title.heared_minutes_by_weekday') }}</h5>
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
                                    label: '{{ __('spotify.minutes.heared') }}',
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
                    <h5 class="card-title">{{ __('spotify.title.heared_minutes_by_daytime') }}</h5>
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
                                        '{{$weekData->hour}} {{ __('spotify.time_suffix') }}',
                                    @endforeach
                                ],
                                datasets: [{
                                    label: '{{ __('spotify.minutes.heared') }}',
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