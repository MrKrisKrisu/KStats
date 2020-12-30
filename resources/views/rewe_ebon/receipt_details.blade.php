@extends('layout.app')

@section('title'){{__('receipts.your_purchase')}} {{ $bon->timestamp_bon->diffForHumans() }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>{{__('receipts.market')}}</td>
                                <td>
                                    {{$bon->shop->name}}<br/>
                                    {{$bon->shop->zip}} {{$bon->shop->city}}
                                </td>
                            </tr>
                            <tr>
                                <td>{{__('general.time')}}</td>
                                <td>{{$bon->timestamp_bon->format('d.m.Y H:i')}}</td>
                            </tr>
                            <tr>
                                <td>{{__('receipts.total')}}</td>
                                <td>{{ number_format($bon->total, 2, ',', '.') }}€</td>
                            </tr>
                            <tr>
                                <td>{{__('receipts.payment_method')}}</td>
                                <td>{{ $bon->paymentmethod }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if($bon->receipt_pdf !== NULL)
                        <a class="btn btn-primary" href="{{ route('download_raw_rewe_receipt', ['id' => $bon->id]) }}">
                            {{__('receipts.dl_receipt')}}
                        </a>
                    @endif

                    @if($bon->raw_bon !== NULL)
                        <hr/>
                        <pre>{{$bon->raw_bon}}</pre>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{__('receipts.product')}}</th>
                                <th>{{__('receipts.amount')}} / {{__('receipts.weight')}}</th>
                                <th>{{__('receipts.price_single')}}</th>
                                <th>{{__('receipts.subtotal')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bon->positions as $position)
                                <tr>
                                    <td>
                                        <a href="{{route('rewe.product', ['id' => $position->product->id])}}">{{$position->product->name}}</a>
                                    </td>
                                    <td>
                                        @if($position->amount)
                                            {{$position->amount}}x
                                        @elseif($position->weight)
                                            {{$position->weight}}kg
                                        @endif
                                    </td>
                                    <td>{{ number_format($position->single_price, 2, ',', '.') }}€</td>
                                    <td>{{ number_format($position->total(), 2, ',', '.') }}€</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection