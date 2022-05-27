<div class="card mb-2">
    <div class="card-body">
        <div id="chartSpentMonth"></div>
        <script>
            new ApexCharts(document.querySelector("#chartSpentMonth"), {
                series: [
                    {
                        name: '{{ __('spent-in-currency') }}',
                        data: [
                                @foreach($monthlySpend as $month => $amount)
                            {
                                x: '{{$month}}',
                                y: {{$amount}}
                            },
                            @endforeach
                        ],
                    }
                ],
                title: {
                    text: '{{__('spent-by-month')}}',
                    style: {
                        color: colorGradients[0]
                    }
                },
                colors: colorGradients,
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " €"
                        }
                    }
                }
            }).render();
        </script>
    </div>
    <div class="card-footer"><small>{{__('receipt-count.text')}}</small></div>
</div>
