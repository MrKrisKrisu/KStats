<div class="card mb-2">
    <div class="card-body">
        <div id="chartMarkets"></div>
        <script>
            new ApexCharts(document.querySelector("#chartMarkets"), {
                series: [
                    @foreach($topMarkets as $row)
                            {{$row['spent']}},
                    @endforeach
                ],
                chart: {
                    type: 'pie',
                },
                labels: [
                    @foreach($topMarkets as $row)
                        '{{$row['shop']->name ?? ""}} {{$row['shop']->city ?? ""}} (Markt {{$row['shop']->id}})',
                    @endforeach
                ],
                title: {
                    text: '{{__('my-markets')}}',
                    style: {
                        color: colorGradients[0],
                    }
                },
                colors: [
                    @foreach($topMarkets as $row)
                            @isset($row['shop']->brand?->primary_color)
                        '{{$row['shop']->brand?->primary_color}}',
                    @else
                        colorGradients[0],
                    @endif
                    @endforeach
                ],
                legend: {
                    show: false,
                }
            }).render();
        </script>
    </div>
    <div class="card-footer"><small>{{__('spending-per-market')}}</small></div>
</div>
