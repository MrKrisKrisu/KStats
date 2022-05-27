<div class="card mb-2">
    <div class="card-body">
        <div id="chartDaytime"></div>
        <script>
            new ApexCharts(document.querySelector("#chartDaytime"), {
                series: [
                    {
                        name: '{{ __('receipt-count') }}',
                        data: [
                                @foreach($receiptsByHour as $hour => $count)
                            {
                                x: '{{$hour}}',
                                y: {{$count}}
                            },
                            @endforeach
                        ],
                    }
                ],
                title: {
                    text: '{{__('receipts-by-daytime')}}',
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
                            return val + " {{__('receipts')}}"
                        }
                    }
                }
            }).render();
        </script>
    </div>
    <div class="card-footer"><small>{{__('receipt-count.text')}}</small></div>
</div>