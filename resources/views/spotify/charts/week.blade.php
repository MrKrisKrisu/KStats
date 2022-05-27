<div class="card mb-2">
    <div class="card-body">
        <div id="chartWeek"></div>
        <script>
            let optionsWeek = {
                series: [
                    {
                        name: '{{ __('minutes') }}',
                        data: [
                                @foreach($chartData_hearedByWeek as $weekData)
                            {
                                x: new Date('{{$weekData->timestamp->toIso8601String()}}').getTime(),
                                y: {{$weekData->minutes}}
                            }@if(!$loop->last),@endif
                            @endforeach
                        ]
                    }
                ],
                title: {
                    text: '{{__('spotify.title.heared_minutes_by_week')}}',
                    style: {
                        color: colorGradients[0]
                    }
                },
                colors: colorGradients,
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
                            return value + " minutes";
                        }
                    },
                    min: 0,
                },
                xaxis: {
                    type: 'datetime',
                    labels: {
                        datetimeUTC: false,
                        datetimeFormatter: {
                            year: 'yyyy',
                            month: 'MMM \'yy',
                            day: 'dd MMM yyyy'
                        }
                    }
                },
                tooltip: {
                    shared: false,
                    x: {
                        format: 'dd MMM yyyy'
                    }
                }
            };

            let chartWeek = new ApexCharts(document.querySelector("#chartWeek"), optionsWeek);
            chartWeek.render();
        </script>
    </div>
</div>