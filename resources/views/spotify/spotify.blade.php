@extends('layout.app')

@section('title')Spotify {{__('spotify.statistic')}} @endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    <div class="row">
                        <div class="col-md-3">
                            <span class="color-primary" style="font-size: 50px;" id="lieblingsjahr">...</span><br/>
                            <small class="text-muted font-weight-bold">{{__('spotify.title.favourite_year')}}</small>
                            <script>
                                $(document).ready(function () {
                                    $.ajax({
                                        url: '/api/spotify/user/favourite_year',
                                        success: function (data) {
                                            $('#lieblingsjahr').html(data);
                                        }
                                    });
                                });
                            </script>
                        </div>
                        <div class="col-md-3">
                            <span class="color-primary" style="font-size: 50px;">
                                <span id="bpm">...</span><small>BPM</small>
                            </span>
                            <br/>
                            <small class="text-muted font-weight-bold">{{__('spotify.title.favourite_bpm')}}</small>
                            <script>
                                $(document).ready(function () {
                                    $.ajax({
                                        url: '/api/spotify/user/average_bpm',
                                        success: function (data) {
                                            $('#bpm').html(data);
                                        }
                                    });
                                });
                            </script>
                        </div>
                        <div class="col-md-3">
                                <span class="color-primary" style="font-size: 50px;"
                                      id="track_count">...</span><br>
                            <small class="text-muted font-weight-bold">{{__('spotify.title.count_tracks')}}</small>
                            <script>
                                $(document).ready(function () {
                                    $.ajax({
                                        url: '/api/spotify/user/track_count',
                                        success: function (data) {
                                            $('#track_count').html(data);
                                        }
                                    });
                                });
                            </script>
                        </div>
                        <div class="col-md-3">
                            <span class="color-primary" style="font-size: 50px;">
                                <span id="avgPerSession">...</span><small>{{__('spotify.minutes.short')}}</small>
                            </span><br>
                            <small class="text-muted font-weight-bold">{{__('spotify.title.avg_session_length')}}</small>
                            <script>
                                $(document).ready(function () {
                                    $.ajax({
                                        url: '/api/spotify/user/average_session_length',
                                        success: function (data) {
                                            $('#avgPerSession').html(data);
                                        }
                                    });
                                });
                            </script>
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
                    <div id="last_played"></div>
                    <script>
                        $(document).ready(function () {
                            $.ajax({
                                url: '/api/spotify/user/last_played',
                                success: function (data) {
                                    var component = '';

                                    component += '<div class="row">';
                                    component += '<div class="col-md-4">';
                                    if (typeof data.track.album.imageUrl !== 'undefined' && data.track.album.imageUrl !== null) {
                                        component += '<a href="/spotify/track/' + data.track.id + '">';
                                        component += '<img src="' + data.track.album.imageUrl + '" class="spotify-cover"/>';
                                        component += '</a>';
                                    }
                                    component += '</div>';
                                    component += '<div class="col">';
                                    component += '<a href="/spotify/track/' + data.track.id + '">';
                                    component += '<b>' + data.track.name + '</b>';
                                    component += '</a>';
                                    component += '<br>';
                                    if (typeof data.track.artists[0].name !== 'undefined' && data.track.artists[0].name !== null) {
                                        component += '<small>von <i>' + data.track.artists[0].name + '</i></small>';
                                        component += '<br/>';
                                    }

                                    if (typeof data.track.preview_url !== 'undefined' && data.track.preview_url !== null) {
                                        component += '<audio controls="">';
                                        component += '<source src="' + data.track.preview_url + '" type="audio/mpeg">';
                                        component += 'Your browser does not support the audio element.';
                                        component += '</audio>';
                                    }
                                    component += '</div>';
                                    component += '</div>';

                                    $("#last_played")
                                        .append(component);
                                }
                            });
                        });
                    </script>
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
                            <td><span id="playtime_total">...</span>min</td>
                        </tr>
                        <tr>
                            <td><b>{{ __('spotify.last_days', ['days' => 30]) }}</b></td>
                            <td><span id="playtime_30">...</span>min</td>
                        </tr>
                        <tr>
                            <td><b>{{ __('spotify.last_days', ['days' => 7]) }}</b></td>
                            <td><span id="playtime_7">...</span>min</td>
                        </tr>
                        </tbody>
                    </table>
                    <script>
                        $(document).ready(function () {
                            $.ajax({
                                url: '/api/spotify/user/playtime',
                                success: function (data) {
                                    $('#playtime_total').html(data);
                                }
                            });

                            $.ajax({
                                url: '/api/spotify/user/playtime/{{\Carbon\Carbon::parse('-30 days')->toDateString()}}',
                                success: function (data) {
                                    $('#playtime_30').html(data);
                                }
                            });

                            $.ajax({
                                url: '/api/spotify/user/playtime/{{\Carbon\Carbon::parse('-7 days')->toDateString()}}',
                                success: function (data) {
                                    $('#playtime_7').html(data);
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body" id="topTracksTotal">
                    <h5 class="card-title">{{ __('spotify.title.top_tracks') }} [{{ __('spotify.total') }}]</h5>
                    @include('spotify.card_topTracks', ['topTracks' => $topTracksTotal, 'fragment' => 'topTracksTotal'])
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body" id="topTracks30">
                    <h5 class="card-title">{{ __('spotify.title.top_tracks') }}
                        [{{ __('spotify.last_days', ['days' => 30]) }}]</h5>
                    @include('spotify.card_topTracks', ['topTracks' => $topTracks30, 'fragment' => 'topTracks30'])
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
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            min: 0
                                        }
                                    }]
                                }
                            }
                        })
                        ;
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
                    <table class="ui table unstackable" id="top_artists_total">
                        <thead>
                        <tr>
                            <th>{{ __('spotify.rank') }}</th>
                            <th>{{ __('spotify.artist') }}</th>
                            <th>{{ __('spotify.heared_minutes') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <script>
                        $(document).ready(function () {
                            $.ajax({
                                url: '/api/spotify/user/top_artists',
                                success: function (data) {
                                    $.each(data, function (index, value) {
                                        $("#top_artists_total").find('tbody')
                                            .append($('<tr>')
                                                .append($('<td>')
                                                    .append($('<span>')
                                                        .text('#' + (index + 1))
                                                    )
                                                )
                                                .append($('<td>')
                                                    .append($('<span>')
                                                        .text(value.name)
                                                    )
                                                )
                                                .append($('<td>')
                                                    .append($('<span>')
                                                        .text(value.minutes + 'min')
                                                    )
                                                )
                                            );
                                    });
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('spotify.title.top_artists') }}
                        [{{ __('spotify.last_days', ['days' => 30]) }}]</h5>

                    <table class="ui table unstackable" id="top_artists_30days">
                        <thead>
                        <tr>
                            <th>{{ __('spotify.rank') }}</th>
                            <th>{{ __('spotify.artist') }}</th>
                            <th>{{ __('spotify.heared_minutes') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <script>
                        $(document).ready(function () {
                            $.ajax({
                                url: '/api/spotify/user/top_artists/{{\Carbon\Carbon::parse('-30 days')->toDateString()}}',
                                success: function (data) {
                                    $.each(data, function (index, value) {
                                        $("#top_artists_30days").find('tbody')
                                            .append($('<tr>')
                                                .append($('<td>')
                                                    .append($('<span>')
                                                        .text('#' + (index + 1))
                                                    )
                                                )
                                                .append($('<td>')
                                                    .append($('<span>')
                                                        .text(value.name)
                                                    )
                                                )
                                                .append($('<td>')
                                                    .append($('<span>')
                                                        .text(value.minutes + 'min')
                                                    )
                                                )
                                            );
                                    });
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('spotify.title.top_artists') }}
                        [{{ __('spotify.last_days', ['days' => 7]) }}]</h5>

                    <table class="ui table unstackable" id="top_artists_7days">
                        <thead>
                        <tr>
                            <th>{{ __('spotify.rank') }}</th>
                            <th>{{ __('spotify.artist') }}</th>
                            <th>{{ __('spotify.heared_minutes') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <script>
                        $(document).ready(function () {
                            $.ajax({
                                url: '/api/spotify/user/top_artists/{{\Carbon\Carbon::parse('-7 days')->toDateString()}}',
                                success: function (data) {
                                    $.each(data, function (index, value) {
                                        $("#top_artists_7days").find('tbody')
                                            .append($('<tr>')
                                                .append($('<td>')
                                                    .append($('<span>')
                                                        .text('#' + (index + 1))
                                                    )
                                                )
                                                .append($('<td>')
                                                    .append($('<span>')
                                                        .text(value.name)
                                                    )
                                                )
                                                .append($('<td>')
                                                    .append($('<span>')
                                                        .text(value.minutes + 'min')
                                                    )
                                                )
                                            );
                                    });
                                }
                            });
                        });
                    </script>
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