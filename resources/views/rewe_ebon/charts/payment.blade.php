<div class="card mb-2">
    <div class="card-body">
        <div id="chartPayment"></div>
        <script>
            new ApexCharts(document.querySelector("#chartPayment"), {
                series: [
                    @foreach($payment_methods as $paymentMethod => $count)
                            {{$count ?? 0}},
                    @endforeach
                ],
                chart: {
                    type: 'pie',
                },
                labels: [
                    @foreach($payment_methods as $paymentMethod => $count)
                        '{{$paymentMethod}}',
                    @endforeach
                ],
                title: {
                    text: '{{__('receipts.payment_methods')}}',
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
    <div class="card-footer"><small>{{__('percent-paymentmethod')}}</small></div>
</div>