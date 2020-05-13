@extends('layout.app')

@section('content')
    <h1 class="page-title">Reweba - REWE eBon Analyzer</h1>
    <div class="row">
        <div class="col" style="font-weight: bold;">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    <div class="row">
                        <div class="col">
                            <span style="color: #38A3A6; font-size: 50px;">{{$bonCount}}</span><br>
                            <small><b>Erfasste Einkäufe</b></small>
                        </div>
                        <div class="col">
                            <span style="color: #38A3A6; font-size: 50px;">{{$mostUsedPaymentMethod}}</span><br>
                            <small><b>Meistgenutzte Zahlungsmethode</b></small>
                        </div>
                        <div class="col">
                                <span style="color: #38A3A6; font-size: 50px;">{{ number_format($avgPer, 2, ',', '.') }}<small>€</small><i
                                            class="mdi mdi-trending-up"
                                            style="font-size: 25px; color: rgb(255, 99, 132)"></i></span><br>
                            <small><b>durchschn. pro Einkauf</b></small>
                        </div>
                        <div class="col">
                            <span style="color: #38A3A6; font-size: 50px;">{{ number_format($total, 2, ',', '.') }}<small>€</small></span><br>
                            <small><b>Insgesamt ausgegeben</b></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    <span style="font-weight: bold;">Sende deine Kassenzettel von einer <a href="/settings/">verifizierten E-Mail Adresse</a> an folgende E-Mail Adresse: <b>{{$ebonKey ?? ''}}@reweebon.k118.de</b></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bezahlmethoden</h5>
                    <div id="chart_payment"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', {'packages': ['corechart']});
                    google.charts.setOnLoadCallback(drawChartShops);

                    function drawChartShops() {

                        var data = google.visualization.arrayToDataTable([
                            ['Shop', 'Anzahl Einkäufe'],
                                @foreach($payment_methods as $pm)
                            ['{{$pm->paymentmethod}}', {{$pm->cnt}}],
                            @endforeach
                        ]);

                        var options = {};

                        var chart = new google.visualization.PieChart(document.getElementById('chart_payment'));

                        chart.draw(data, options);
                    }
                </script>
                <div class="card-footer"><small>Umsatz je Zahlungsmethode in Prozent</small></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Meine Märkte</h5>
                    <div id="chart_shops"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', {'packages': ['corechart']});
                    google.charts.setOnLoadCallback(drawChartShops);

                    function drawChartShops() {

                        var data = google.visualization.arrayToDataTable([
                            ['Shop', 'Anzahl Einkäufe'],
                                @foreach($shops as $shop)
                            ['{{$shop->shop_id}}', {{$shop->cnt}}],
                            @endforeach
                        ]);

                        var options = {};

                        var chart = new google.visualization.PieChart(document.getElementById('chart_shops'));

                        chart.draw(data, options);
                    }
                </script>
                <div class="card-footer"><small>Anzahl an Einkäufen im Markt</small></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ernährung</h5>
                    <div id="chart_vegetarian"></div>
                </div>
                <script type="text/javascript">
                    google.charts.load('current', {'packages': ['corechart']});
                    google.charts.setOnLoadCallback(drawChartVegetarian);

                    function drawChartVegetarian() {

                        var data = google.visualization.arrayToDataTable([
                            ['Art', 'Anzahl Käufe'],
                                @foreach($products_vegetarian as $pm)
                            ['{{$pm->vegetarian === NULL ? 'Unbekannt' : str_replace(array('-1', '0', '1'), array('Kein Lebensmittel', 'Nicht vegetarisch', 'vegetarisch'), $pm->vegetarian)}}', {{$pm->cnt}}],
                            @endforeach
                        ]);

                        var options = {};

                        var chart = new google.visualization.PieChart(document.getElementById('chart_vegetarian'));

                        chart.draw(data, options);
                    }
                </script>
                <div class="card-footer"><small>Anzahl gekaufter Produkte</small></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Lieblingsprodukte</h5>
                    <table class="table" id="lieblingsprodukte">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Anzahl</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($favouriteProducts as $product)
                            <tr>
                                <td>{{$product->name}}</td>
                                <td>{{$product->cnt}}x</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <script>
                        $('#lieblingsprodukte').DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/German.json"
                            },
                            "order": [[1, 'desc']],
                            "pageLength": 5,
                            "lengthMenu": [5, 10, 25, 50, 75, 100]
                        });
                    </script>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kaufvorhersage</h5>
                    <table class="table" id="kaufvorhersage">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Zuletzt gekauft</th>
                            <th>Nächster Kauf vrsl.</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($forecast as $product)
                            <tr>
                                <td>{{$product->name}}</td>
                                <td>{{Carbon\Carbon::parse($product->lastTS)->diffForHumans()}}</td>
                                <td>{{Carbon\Carbon::parse($product->nextTS)->diffForHumans()}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <script>
                        $('#kaufvorhersage').DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/German.json"
                            },
                            "order": [[1, 'asc']],
                            "pageLength": 5,
                            "lengthMenu": [5, 10, 25, 50, 75, 100]
                        });
                    </script>
                    <small>Wenn du längere Zeit dieses Tool nutzt, wird dir hier eine
                        Kaufvorhersage angezeigt. Basierend auf deinen zuletzt
                        gekauften Produkten und dem Intervall dieser Käufe.</small>
                    <!--<a target="_blank"
                       href="https://k118.de/rewe/forecastICS/?key=">ICS DEV</a>-->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kassenzettel</h5>
                    <table class="table" id="kassenzettel">
                        <thead>
                        <tr>
                            <th>MarktNr.</th>
                            <th>Kasse</th>
                            <th>Zahlungsart</th>
                            <th>Gesamtbetrag</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($bonList as $bon)
                            <tr>
                                <td>{{$bon->shop_id}}</td>
                                <td>{{$bon->cashregister_nr}}</td>
                                <td>{{$bon->paymentmethod}}</td>
                                <td>{{$bon->total}}</td>
                                <td><a href="{{ route('rewe_receipt', [$bon->id]) }}">Details</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <script>
                        $('#kassenzettel').DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/German.json"
                            },
                            "order": [[1, 'desc']],
                            "pageLength": 5,
                            "lengthMenu": [5, 10, 25, 50, 75, 100]
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Einkauf nach Tageszeit</h5>
                    <div id="chart_dayTime"></div>
                </div>
                <div class="card-footer"><small>Anzahl an Einkäufen pro Stunde</small></div>
            </div>

            <script>
                google.charts.load('current', {packages: ['corechart', 'bar']});
                google.charts.setOnLoadCallback(drawMultSeries);

                function drawMultSeries() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('timeofday', 'Time of Day');
                    data.addColumn('number', 'Anzahl Einkäufe');

                    data.addRows([
                            @foreach($shoppingByHour as $data)
                        [{v: [{{$data->hour}}, 0, 0], f: '{{$data->hour}} Uhr'}, {{$data->cnt}}],
                        @endforeach
                    ]);

                    var options = {
                        hAxis: {
                            title: 'Zeit',
                            format: 'H',
                            viewWindow: {
                                min: [0, 0, 0],
                                max: [23, 59, 0]
                            }
                        }
                    };

                    var chart = new google.visualization.ColumnChart(
                        document.getElementById('chart_dayTime'));

                    chart.draw(data, options);
                }
            </script>
        </div>
    </div>

    <!--<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Einkaufswert pro Einkauf</h5>
                    <p>Dieser Statistik steht in Kürze wieder zur Verfügung.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ausgaben nach Kategorie</h5>
                    <p>Dieser Statistik steht in Kürze wieder zur Verfügung.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top Kategorien (nach Anzahl Produkte)</h5>
                    <p>Dieser Statistik steht in Kürze wieder zur Verfügung.</p>
                </div>
            </div>
        </div>
    </div>-->

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Produktkategorien nach Umsatz</h5>
                    <div id="chart_categoryPrice"></div>
                    <script type="text/javascript">
                        google.charts.load('current', {'packages': ['corechart']});
                        google.charts.setOnLoadCallback(drawChartCategoryPrice);

                        function drawChartCategoryPrice() {

                            var data = google.visualization.arrayToDataTable([
                                ['Kategorie', 'Ausgaben'],
                                    @foreach($topByCategoryPrice as $cc)
                                ['{{$cc->category_name}}', {{$cc->price}}],
                                @endforeach
                            ]);

                            var options = {};

                            var chart = new google.visualization.PieChart(document.getElementById('chart_categoryPrice'));

                            chart.draw(data, options);
                        }
                    </script>
                </div>
                <div class="card-footer">
                    <small>Ausgaben (in €) in den jeweiligen Kategorien seit Beginn der Aufzeichnung. Dieses
                        Diagramm wird
                        durch <a href="{{route('crowdsourcing_rewe')}}">Crowdsourcing</a> ermöglicht.</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Produktkategorien nach Anzahl</h5>
                    <div id="chart_categoryCount"></div>
                    <script type="text/javascript">
                        google.charts.load('current', {'packages': ['corechart']});
                        google.charts.setOnLoadCallback(drawChartCategoryCount);

                        function drawChartCategoryCount() {

                            var data = google.visualization.arrayToDataTable([
                                ['Kategorie', 'Anzahl Käufe'],
                                    @foreach($topByCategoryCount as $cc)
                                ['{{$cc->category_name}}', {{$cc->cnt}}],
                                @endforeach
                            ]);

                            var options = {};

                            var chart = new google.visualization.PieChart(document.getElementById('chart_categoryCount'));

                            chart.draw(data, options);
                        }
                    </script>
                </div>
                <div class="card-footer">
                    <small>Dieses Diagramm wird durch <a href="{{route('crowdsourcing_rewe')}}">Crowdsourcing</a>
                        ermöglicht.</small>
                </div>
            </div>
        </div>
    </div>
@endsection