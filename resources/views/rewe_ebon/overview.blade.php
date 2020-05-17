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
                    <canvas id="chart_payment"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var chart_payment = document.getElementById('chart_payment').getContext('2d');
                        window.chart_payment = new Chart(chart_payment, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    backgroundColor: ["#38A2A6", "#4FD6E8", "#63E0FF", "#4FBDE8", "#57C1FF", "#4FBDE8", "#63E0FF", "#4FD6E8", "#57F9FF"],
                                    data: [
                                        @foreach($payment_methods as $pm)
                                            '{{$pm->cnt}}',
                                        @endforeach
                                    ],
                                    label: 'Zahlungsart'
                                }],
                                labels: [
                                    @foreach($payment_methods as $pm)
                                        '{{$pm->paymentmethod}}',
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
                                }
                            }
                        });
                    });
                </script>
                <div class="card-footer"><small>Umsatz je Zahlungsmethode in Prozent</small></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Meine Märkte</h5>
                    <canvas id="chart_shops"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var chart_shops = document.getElementById('chart_shops').getContext('2d');
                        window.chart_shops = new Chart(chart_shops, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    backgroundColor: ["#38A2A6", "#4FD6E8", "#63E0FF", "#4FBDE8", "#57C1FF", "#4FBDE8", "#63E0FF", "#4FD6E8", "#57F9FF"],
                                    data: [
                                        @foreach($shops as $shop)
                                            '{{$shop->cnt}}',
                                        @endforeach
                                    ],
                                    label: 'Markt Nr.'
                                }],
                                labels: [
                                    @foreach($shops as $shop)
                                        '{{$shop->shop->name ?? "Markt " . $shop->shop->id}}',
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
                                }
                            }
                        });
                    });
                </script>
                <div class="card-footer"><small>Anzahl an Einkäufen im Markt</small></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ernährung</h5>
                    <canvas id="chart_vegetarian"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var chart_vegetarian = document.getElementById('chart_vegetarian').getContext('2d');
                        window.chart_vegetarian = new Chart(chart_vegetarian, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    backgroundColor: ["#38A2A6", "#4FD6E8", "#63E0FF", "#4FBDE8", "#57C1FF", "#4FBDE8", "#63E0FF", "#4FD6E8", "#57F9FF"],
                                    data: [
                                        @foreach($products_vegetarian as $pv)
                                            '{{$pv->cnt}}',
                                        @endforeach
                                    ]
                                }],
                                labels: [
                                    @foreach($products_vegetarian as $pv)
                                        '{{$pv->vegetarian === NULL ? 'Unbekannt' : str_replace(array('-1', '0', '1'), array('Kein Lebensmittel', 'Nicht vegetarisch', 'vegetarisch'), $pv->vegetarian)}}',
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
                                }
                            }
                        });
                    });
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
                    <canvas id="chart_dayTime"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var chart_dayTime = document.getElementById('chart_dayTime').getContext('2d');
                        window.chart_dayTime = new Chart(chart_dayTime, {
                            type: 'bar',
                            data: {
                                labels: [
                                    @for($hour = 0; $hour < 24; $hour++)
                                        '{{$hour}} Uhr',
                                    @endfor
                                ],
                                datasets: [{
                                    label: 'Anzahl Einkäufe',
                                    backgroundColor: '#38a3a6',
                                    borderWidth: 1,
                                    data: [
                                        @for($hour = 0; $hour < 24; $hour++)
                                        {{$shoppingByHour[$hour]}},
                                        @endfor
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
                                }
                            }
                        });
                    });
                </script>
                <div class="card-footer"><small>Anzahl an Einkäufen pro Stunde</small></div>
            </div>
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
                    <canvas id="chart_categoryPrice"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var chart_categoryPrice = document.getElementById('chart_categoryPrice').getContext('2d');
                        window.chart_categoryPrice = new Chart(chart_categoryPrice, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    backgroundColor: ["#38A2A6", "#4FD6E8", "#63E0FF", "#4FBDE8", "#57C1FF", "#4FBDE8", "#63E0FF", "#4FD6E8", "#57F9FF"],
                                    data: [
                                        @foreach($topByCategoryPrice as $cc)
                                            '{{$cc->price}}',
                                        @endforeach
                                    ],
                                    label: 'Zahlungsart'
                                }],
                                labels: [
                                    @foreach($topByCategoryPrice as $cc)
                                        '{!! $cc->category_name !!}',
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
                                }
                            }
                        });
                    });
                </script>
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
                    <canvas id="chart_categoryCount"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var chart_categoryCount = document.getElementById('chart_categoryCount').getContext('2d');
                        window.chart_categoryCount = new Chart(chart_categoryCount, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    backgroundColor: ["#38A2A6", "#4FD6E8", "#63E0FF", "#4FBDE8", "#57C1FF", "#4FBDE8", "#63E0FF", "#4FD6E8", "#57F9FF"],
                                    data: [
                                        @foreach($topByCategoryCount as $cc)
                                            '{{$cc->cnt}}',
                                        @endforeach
                                    ],
                                    label: 'Zahlungsart'
                                }],
                                labels: [
                                    @foreach($topByCategoryCount as $cc)
                                        '{!! $cc->category_name !!}',
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
                                }
                            }
                        });
                    });
                </script>
                <div class="card-footer">
                    <small>Dieses Diagramm wird durch <a href="{{route('crowdsourcing_rewe')}}">Crowdsourcing</a>
                        ermöglicht.</small>
                </div>
            </div>
        </div>
    </div>
@endsection
