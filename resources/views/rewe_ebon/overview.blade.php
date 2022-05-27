@extends('layout.app')

@section('title', __('rewe-analyzer'))

@section('before-title')
    <a class="float-end btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-upload">
        <i class="fas fa-upload"></i> {{__('upload-receipt')}}
    </a>
@endsection

@section('footer')
    @parent
    <div class="modal" tabindex="-1" role="dialog" id="modal-upload">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-upload"></i> {{__('upload-receipt')}}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col">
                            <h3>{{__('manual-method')}}</h3>

                            <form method="POST" enctype="multipart/form-data"
                                  action="{{route('receipt.import.upload')}}">
                                @csrf
                                <div class="mb-2">
                                    <label>{{__('receipt')}} <small>(.pdf)</small></label>
                                    <input id="file" type="file" class="form-control" name="file" required>
                                </div>

                                <button type="submit" class="btn btn-primary">{{__('upload')}}</button>
                            </form>
                        </div>
                        <div class="col">
                            <h3>{{__('automatic-method')}}</h3>
                            <small>{{__('receipt.automatic.explain')}}</small>
                            <hr/>
                            <p>{{__('recipient')}}: <b>{{$ebonKey ?? ''}}@reweebon.k118.de</b></p>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">{{__('close')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col" style="font-weight: bold;">
            <div class="card mb-2">
                <div class="card-body" style="text-align: center;">
                    <div class="row">
                        <div class="col-md-3">
                            <span class="color-highlight" style="font-size: 40px;">
                                {{auth()->user()->reweReceipts->count()}}
                            </span>
                            <br>
                            <small><b>{{__('receipts.count')}}</b></small>
                        </div>
                        <div class="col-md-3">
                            <span class="color-highlight" style="font-size: 40px;">
                                {{$mostUsedPaymentMethod}}
                            </span>
                            <br>
                            <small><b>{{__('most-used-payment-methods')}}</b></small>
                        </div>
                        <div class="col-md-3">
                                <span class="color-highlight" style="font-size: 40px;">
                                    {{ number_format(auth()->user()->reweReceipts->avg('total'), 2, ',', '.') }}
                                    <small>€</small>
                                    <i class="mdi mdi-trending-up"
                                       style="font-size: 25px; color: rgb(255, 99, 132)"></i>
                                </span>
                            <br>
                            <small><b>{{__('avg-receipt-price')}}</b></small>
                        </div>
                        <div class="col-md-3">
                            <span class="color-highlight" style="font-size: 40px;">
                                {{ number_format(auth()->user()->reweReceipts->sum('total'), 2, ',', '.') }}
                                <small>€</small>
                            </span>
                            <br>
                            <small><b>{{__('payed-total')}}</b></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            @include('rewe_ebon.charts.payment')
        </div>
        <div class="col-md-4">
            @include('rewe_ebon.charts.markets')
        </div>
        <div class="col-md-4">
            @include('rewe_ebon.charts.vegetarian')
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('product-favourite')}}</h5>
                    <table class="table" id="lieblingsprodukte">
                        <thead>
                            <tr>
                                <th>{{__('receipts.product')}}</th>
                                <th>{{__('receipts.amount')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($favouriteProducts as $product)
                                <tr>
                                    <td>
                                        <a href="{{route('rewe.product', ['id' => $product->id])}}">
                                            {{$product->name}}
                                        </a>
                                    </td>
                                    <td data-order="{{$product->cnt}}">{{$product->cnt}}x</td>
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
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('product.prediction')}}</h5>
                    <table class="table" id="kaufvorhersage">
                        <thead>
                            <tr>
                                <th>{{__('receipts.product')}}</th>
                                <th>{{__('receipts.last_bought')}}</th>
                                <th>{{__('product.next-buy')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forecast as $product)
                                <tr>
                                    <td>
                                        <a href="{{route('rewe.product', ['id' => $product->id])}}">
                                            {{$product->name}}
                                        </a>
                                    </td>
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
                    <small>{{__('product.prediction.text')}}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('receipt')}}</h5>
                    <table class="table" id="kassenzettel">
                        <thead>
                            <tr>
                                <th>{{__('time')}}</th>
                                <th>{{__('receipts.market')}}</th>
                                <th>{{__('cash-register')}}</th>
                                <th>{{__('receipts.payment_method')}}</th>
                                <th>{{__('receipts.price_total')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach(auth()->user()->reweReceipts->sortByDesc('timestamp_bon') as $bon)
                                <tr>
                                    <td data-order="{{$bon->timestamp_bon}}">{{$bon->timestamp_bon->format('d.m.Y H:i')}}</td>
                                    <td>
                                        <a href="{{route('rewe.shop', ['id' => $bon->shop->id])}}">
                                            Markt {{$bon->shop->id}}<br/>
                                            <small>in {{$bon->shop->zip}} {{$bon->shop->city}}</small>
                                        </a>
                                    </td>
                                    <td>{{$bon->cashregister_nr}}</td>
                                    <td>{{$bon->paymentmethod}}</td>
                                    <td>{{number_format($bon->total, 2, ",", ".")}} €</td>
                                    <td><a href="{{ route('rewe_receipt', [$bon->id]) }}">{{__('details')}}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <script>
                        $('#kassenzettel').DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/German.json"
                            },
                            "order": [[0, 'desc']],
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
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('receipts-by-daytime')}}</h5>
                    <canvas id="chart_dayTime"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        let chart_dayTime = document.getElementById('chart_dayTime').getContext('2d');
                        window.chart_dayTime = new Chart(chart_dayTime, {
                            type: 'bar',
                            data: {
                                labels: [
                                    @for($hour = 0; $hour < 24; $hour++)
                                        '{{$hour}} Uhr',
                                    @endfor
                                ],
                                datasets: [{
                                    label: '{{__('receipt-count')}}',
                                    backgroundColor: colorGradients[0],
                                    borderWidth: 1,
                                    data: [
                                        @foreach(auth()->user()->reweReceipts->groupBy(function ($item, $key) {
            return $item['timestamp_bon']->hour;
        })->union([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23])->sortKeys() as $hourly)
                                                @if($hourly instanceof \Illuminate\Database\Eloquent\Collection)
                                                {{$hourly->count()}},
                                        @else
                                            0,
                                        @endif
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
                                }
                            }
                        });
                    });
                </script>
                <div class="card-footer"><small>{{__('receipt-count.text')}}</small></div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('spent-by-month')}}</h5>
                    <canvas id="chart_monthlySpend"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        window.chart_dayTime = new Chart(document.getElementById('chart_monthlySpend').getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: [
                                    @foreach($monthlySpend as $month => $amount)
                                        '{{$month}}',
                                    @endforeach
                                ],
                                datasets: [{
                                    label: '{{__('spent-in-currency')}}',
                                    backgroundColor: colorGradients[0],
                                    borderWidth: 1,
                                    data: [
                                        @foreach($monthlySpend as $month => $amount)
                                                {{$amount}},
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
                                }
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>

    <!--<div class="row">
        <div class="col-md-12">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">Einkaufswert pro Einkauf</h5>
                    <p>Dieser Statistik steht in Kürze wieder zur Verfügung.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">Ausgaben nach Kategorie</h5>
                    <p>Dieser Statistik steht in Kürze wieder zur Verfügung.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">Top Kategorien (nach Anzahl Produkte)</h5>
                    <p>Dieser Statistik steht in Kürze wieder zur Verfügung.</p>
                </div>
            </div>
        </div>
    </div>-->

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('category-by-spent')}}</h5>
                    <canvas id="chart_categoryPrice"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        let chart_categoryPrice = document.getElementById('chart_categoryPrice').getContext('2d');
                        window.chart_categoryPrice = new Chart(chart_categoryPrice, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    backgroundColor: colorGradients,
                                    data: [
                                        @foreach($topByCategoryPrice as $cc)
                                            '{{$cc->price}}',
                                        @endforeach
                                    ],
                                    label: '{{__('receipts.payment_method')}}'
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
                    <small>{{__('category-by-spent.text')}} {{__('chart.crowdsourcing')}}</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('category-by-count')}}</h5>
                    <canvas id="chart_categoryCount"></canvas>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        let chart_categoryCount = document.getElementById('chart_categoryCount').getContext('2d');
                        window.chart_categoryCount = new Chart(chart_categoryCount, {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    backgroundColor: colorGradients,
                                    data: [
                                        @foreach($topByCategoryCount as $cc)
                                            '{{$cc->cnt}}',
                                        @endforeach
                                    ],
                                    label: '{{__('receipts.payment_method')}}'
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
                    <small>{{__('chart.crowdsourcing')}}</small>
                </div>
            </div>
        </div>
    </div>
@endsection
