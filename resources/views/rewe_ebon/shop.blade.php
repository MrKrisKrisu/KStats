@extends('layout.app')

@section('title') REWE Markt {{$shop->id}} in {{$shop->zip}} {{$shop->city}} @endsection

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card mb-2">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{__('time')}}</th>
                                <th>{{__('cash-register')}}</th>
                                <th>{{__('receipts.payment_method')}}</th>
                                <th>{{__('receipts.price_total')}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $receipt)
                                <tr>
                                    <td>{{$receipt->timestamp_bon->format('d.m.Y H:i')}}</td>
                                    <td>{{$receipt->cashregister_nr}}</td>
                                    <td>{{$receipt->paymentmethod}}</td>
                                    <td>{{number_format($receipt->total, 2, ",", ".")}} €</td>
                                    <td>
                                        <a href="{{ route('rewe_receipt', [$receipt->id]) }}">
                                            {{__('details')}}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$history->links()}}
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card mb-2">
                <div class="card-body" style="font-size: 16px;">
                    <h2>Einkaufsfreunde</h2>
                    @if($countOther == 0)
                        <p>Du bist bisher <b>der einzige KStats-Nutzer</b>, welcher in diesem REWE-Markt einkaufen war!
                        </p>
                    @elseif($countOther == 1)
                        <p>Du hast <b>einen</b> Einkaufsfreund, denn neben dir hat <b>ein weiterer KStats-Nutzer</b> in
                            diesem
                            REWE-Markt eingekauft.</p>
                    @else
                        <p>Dieser REWE-Markt scheint beliebt zu sein. Neben dir haben hier bereits <b>{{$countOther}}
                                andere KStats-Nutzer</b> eingekauft!</p>
                    @endif
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-body">
                    <h2>{{$shop->name}}</h2>
                    {{$shop->address}}<br/>
                    {{$shop->zip}} {{$shop->city}}<br/>
                    <hr/>
                    @isset($shop->phone)
                        {{$shop->phone}}<br/>
                    @endisset
                    @isset($shop->opening_hours)
                        {{$shop->opening_hours}}<br/>
                    @endisset
                </div>
            </div>
        </div>
    </div>
@endsection