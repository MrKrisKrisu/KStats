<div class="card mb-2">
    <div class="card-body">
        <div id="chartWeekly"></div>
        <script>
            let optionsWeekly = {
                series: [
                    {
                        name: '{{ __('minutes') }}',
                        data: [
                                @foreach($chartData_hearedByWeekday as $weekData)
                            {
                                x: '{{\App\Http\Controllers\SpotifyController::getWeekdayName($weekData->weekday)}}',
                                y: {{$weekData->minutes}}
                            }@if(!$loop->last),@endif
                            @endforeach
                        ],
                    }
                ],
                title: {
                    text: '{{__('spotify.title.heared_minutes_by_weekday')}}',
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

            let chartWeekly = new ApexCharts(document.querySelector("#chartWeekly"), optionsWeekly);
            chartWeekly.render();
        </script>
    </div>
</div>