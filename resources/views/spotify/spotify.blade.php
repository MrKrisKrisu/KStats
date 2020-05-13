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
                        <div class="col-md-4">
                            <img src="{{$lastPlayActivity->track->album->imageUrl ?? "TODO URL Missing Image"}}"
                                 class="cover"/>
                        </div>
                        <div class="col">
                            <b>{{$lastPlayActivity->track->name ?? "Unknown Song"}}</b><br>
                            <small>von <i>{{$lastPlayActivity->track->artists[0]->name ?? "Unknown Artist"}}</i></small>
                            <hr>
                            @if($lastPlayActivity->track->preview_url != NULL)
                                <audio controls="">
                                    <source src="{{$lastPlayActivity->track->preview_url}}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @else
                                <p>Keine Preview verfügbar.</p>
                            @endif

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
                            <div class="col-md-4">
                                @isset($ttList->track->album->imageUrl)
                                    <img src="{{$ttList->track->album->imageUrl}}" class="cover"/>
                                @endisset
                            </div>
                            <div class="col">
                                <b>{{$ttList->track->name}}</b><br>
                                <small>von <i>{{$ttList->track->artists[0]->name}}</i></small><br/>
                                <small>{{$ttList->minutes}} Minuten gehört</small>
                                <hr>
                                @if($ttList->track->preview_url != NULL)
                                    <audio controls="">
                                        <source src="{{$ttList->track->preview_url}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @else
                                    <p>Keine Preview verfügbar.</p>
                                @endif

                            </div>
                        </div>
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
                            <div class="col-md-4">
                                <img src="{{$ttList->track->album->imageUrl}}" class="cover"/>
                            </div>
                            <div class="col">
                                <b>{{$ttList->track->name}}</b><br>
                                <small>von <i>{{$ttList->track->artists[0]->name}}</i></small><br/>
                                <small>{{$ttList->minutes}} Minuten gehört</small>
                                <hr>
                                @if($ttList->track->preview_url != NULL)
                                    <audio controls="">
                                        <source src="{{$ttList->track->preview_url}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @else
                                    <p>Keine Preview verfügbar.</p>
                                @endif

                            </div>
                        </div>
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
                    <div id="chart_hearedByWeek" style="width: 100%; height: 400px;"></div>
                </div>
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
                <div class="card-body" id="chart_barWeekday" style="height: 400px;">
                    <h5 class="card-title">Gehörte Minuten nach Wochentag</h5>
                    <div id="listenedByWeekday"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card" style="margin-top: 10px;">
                <div class="card-body" style="height: 400px;">
                    <h5 class="card-title">Gehörte Minuten nach Uhrzeit</h5>
                    <div id="listenedByHour"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Week', 'Minutes listened to music'],
                    @foreach($chartData_hearedByWeek as $weekData)
                ['{{$weekData->week}}', {{$weekData->minutes}}],
                @endforeach
            ]);

            var options = {
                curveType: 'function',
                legend: {position: 'bottom'}
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_hearedByWeek'));

            chart.draw(data, options);
        }


        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawWeekdayChart);

        function drawWeekdayChart() {
            var data = google.visualization.arrayToDataTable([
                ['Weekday', 'Minutes listened to music'],
                    @foreach($chartData_hearedByWeekday as $weekData)
                ['{{\App\Http\Controllers\SpotifyController::getWeekdayName($weekData->weekday)}}', {{$weekData->minutes}}],
                @endforeach
            ]);

            var materialOptions = {
                vAxis: {
                    minValue: 0,
                },
                legend: {position: 'none'}
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('listenedByWeekday'));
            chart.draw(data, materialOptions);
        }


        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawHourlyChart);

        function drawHourlyChart() {
            var data = google.visualization.arrayToDataTable([
                ['Weekday', 'Minutes listened to music'],
                    @foreach($chartData_hearedByHour as $weekData)
                ['{{$weekData->hour}} Uhr', {{$weekData->minutes}}],
                @endforeach
            ]);

            var materialOptions = {
                vAxis: {
                    minValue: 0,
                },
                legend: {position: 'none'}
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('listenedByHour'));
            chart.draw(data, materialOptions);
        }
    </script>
@endsection