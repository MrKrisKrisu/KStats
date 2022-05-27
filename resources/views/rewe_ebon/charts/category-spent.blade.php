<div class="card mb-2">
    <div class="card-body">
        <div id="chartCategorySpent"></div>
        <script>
            new ApexCharts(document.querySelector("#chartCategorySpent"), {
                series: [
                    @foreach($topByCategoryPrice as $cc)
                            {{$cc->price}},
                    @endforeach
                ],
                chart: {
                    type: 'pie',
                },
                labels: [
                    @foreach($topByCategoryPrice as $cc)
                        '{{ $cc->category_name }}',
                    @endforeach
                ],
                title: {
                    text: '{{__('category-by-spent')}}',
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
    <div class="card-footer"><small>{{__('chart.crowdsourcing')}}</small></div>
</div>
