<div class="card mb-2">
    <div class="card-body">
        <div id="chartCategoryAmount"></div>
        <script>
            new ApexCharts(document.querySelector("#chartCategoryAmount"), {
                series: [
                    @foreach($topByCategoryCount as $cc)
                            {{$cc->cnt}},
                    @endforeach
                ],
                chart: {
                    type: 'pie',
                },
                labels: [
                    @foreach($topByCategoryCount as $cc)
                        '{{ $cc->category_name }}',
                    @endforeach
                ],
                title: {
                    text: '{{__('category-by-count')}}',
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
