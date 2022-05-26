@extends('layout.app')

@section('title', $product->name)

@section('content')
    <div class="row">
        <div class="col-md-12" style="font-weight: bold;">
            <div class="card mb-2">
                <div class="card-body" style="text-align: center;">
                    <div class="row">
                        <div class="col">
                            <span class="color-highlight" style="font-size: 40px;">
                                @isset($mainStats->weight)
                                    {{$mainStats->weight}} kg
                                @else
                                    {{$mainStats->amount}} x
                                @endisset
                            </span>
                            <br/>
                            <small><b>{{__('already-bought')}}</b></small>
                        </div>

                        <div class="col">
                            <span class="color-highlight" style="font-size: 40px;">
                                {{number_format($mainStats->single_price, 2, ',', '.')}} €
                            </span>
                            <br/>
                            <small><b>{{__('avg-price')}}</b></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{__('receipts.market')}}</th>
                                <th>{{__('time')}}</th>
                                <th>{{__('receipts.amount')}} / {{__('receipts.weight')}}</th>
                                <th>{{__('receipts.price_single')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $position)
                                <tr>
                                    <td>
                                        {{__('receipts.market')}} {{$position->bon->shop->id}}<br/>
                                        <small>{{$position->bon->shop->zip}} {{$position->bon->shop->city}}</small>
                                    </td>
                                    <td>{{$position->receipt->timestamp_bon->format('d.m.Y H:i')}}</td>
                                    <td>
                                        @isset($position->weight)
                                            {{$position->weight}} kg
                                        @else
                                            {{$position->amount}}x
                                        @endisset
                                    </td>
                                    <td>{{number_format($position->single_price, 2, ',', '.')}} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$history->links()}}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-body">
                    <div id="chartPrice"></div>
                    <script>
                        let options = {
                            series: [
                                {
                                    name: 'Einzelpreis',
                                    data: [
                                            @foreach($historyAll as $position)
                                        {
                                            x: new Date('{{$position->receipt->timestamp_bon->toIso8601String()}}').getTime(),
                                            y: {{round($position->single_price, 2) }}
                                        }@if(!$loop->last),@endif
                                        @endforeach
                                    ]
                                }
                            ],
                            chart: {
                                type: 'area',
                                stacked: false,
                                height: 350,
                                zoom: {
                                    type: 'x',
                                    enabled: true,
                                    autoScaleYaxis: true
                                },
                                toolbar: {
                                    autoSelected: 'zoom'
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            markers: {
                                size: 0,
                            },
                            title: {
                                text: 'Preisverlauf',
                                align: 'left'
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 1,
                                    inverseColors: false,
                                    opacityFrom: 0.5,
                                    opacityTo: 0,
                                    stops: [0, 90, 100]
                                },
                            },
                            yaxis: {
                                labels: {
                                    formatter: function (value) {
                                        return value + " €";
                                    }
                                },
                            },
                            xaxis: {
                                type: 'datetime',
                                labels: {
                                    datetimeUTC: false,
                                    datetimeFormatter: {
                                        year: 'yyyy',
                                        month: 'MMM \'yy',
                                        day: 'dd MMM',
                                        hour: 'HH:mm'
                                    }
                                }
                            },
                            tooltip: {
                                shared: false,
                                x: {
                                    format: 'dd MMM yyyy HH:mm'
                                }
                            }
                        };

                        var chart = new ApexCharts(document.querySelector("#chartPrice"), options);
                        chart.render();
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection