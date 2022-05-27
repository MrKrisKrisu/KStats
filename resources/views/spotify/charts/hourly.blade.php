<div class="card mb-2">
    <div class="card-body">
        <div id="chartPrice"></div>
        <script>
            let options = {
                series: [
                    {
                        name: '{{ __('minutes') }}',
                        data: [
                                @foreach($chartData_hearedByHour as $weekData)
                            {
                                x: '{{$weekData->hour}}',
                                y: {{$weekData->minutes}}
                            }@if(!$loop->last),@endif
                            @endforeach
                        ],
                    }
                ],
                title: {
                    text: '{{__('spotify.title.heared_minutes_by_daytime')}}',
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
                            return val + " {{__('minutes')}}"
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chartPrice"), options);
            chart.render();
        </script>
    </div>
</div>