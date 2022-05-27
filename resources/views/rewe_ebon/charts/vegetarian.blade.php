<div class="card mb-2">
    <div class="card-body">
        <div id="chartVegetarian"></div>
        <script>
            new ApexCharts(document.querySelector("#chartVegetarian"), {
                series: [
                    @foreach($products_vegetarian as $pv)
                            {{$pv->cnt}},
                    @endforeach
                ],
                chart: {
                    type: 'pie',
                },
                labels: [
                    @foreach($products_vegetarian as $pv)
                        '{{$pv->vegetarian === null ? 'Unbekannt' : str_replace(array('-1', '0', '1'), array('Kein Lebensmittel', 'Nicht vegetarisch', 'vegetarisch'), $pv->vegetarian)}}',
                    @endforeach
                ],
                title: {
                    text: '{{__('nutrition')}}',
                    style: {
                        color: colorGradients[0]
                    }
                },
                colors: colorGradients,
                legend: {
                    show: false,
                }
            }).render();
        </script>
    </div>
    <div class="card-footer"><small>{{__('product-count')}}</small></div>
</div>
