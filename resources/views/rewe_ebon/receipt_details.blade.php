@extends('layout.app')

@section('title')Dein Einkauf {{ $bon->timestamp_bon->diffForHumans() }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td>Markt</td>
                            <td>
                                {{$bon->shop->name}}<br/>
                                {{$bon->shop->zip}} {{$bon->shop->city}}
                            </td>
                        </tr>
                        <tr>
                            <td>Zeit</td>
                            <td>{{$bon->timestamp_bon->format('d.m.Y H:i')}}</td>
                        </tr>
                        <tr>
                            <td>Gesamtsumme</td>
                            <td>{{ number_format($bon->total, 2, ',', '.') }}€</td>
                        </tr>
                        <tr>
                            <td>Zahlungsart</td>
                            <td>{{ $bon->paymentmethod }}</td>
                        </tr>
                        </tbody>
                    </table>

                    @if($bon->receipt_pdf !== NULL)
                        <a class="btn btn-primary" href="{{ route('download_raw_rewe_receipt', ['id' => $bon->id]) }}">Bon
                            herunterladen</a>
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
                    <table class="table" id="kassenzettel">
                        <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Anzahl / Gewicht</th>
                            <th>Einzelpreis</th>
                            <th>Zwischensumme</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bon->positions as $position)
                            <tr>
                                <td>
                                    <!--<a href="{ {route('rewe_product', [$position->product->id])}}">-->{{$position->product->name}}<!--</a>-->
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