@extends('layout.app')

@section('title', __('shopping-statistic'))

@section('before-title')
    <a class="float-end btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-upload">
        <i class="fas fa-upload"></i> {{__('upload-receipt')}}
    </a>
@endsection

@section('footer')
    @parent
    <div class="modal" tabindex="-1" role="dialog" id="modal-upload">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-upload"></i> {{__('upload-receipt')}}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col">
                            <h3>{{__('manual-method')}}</h3>

                            <form method="POST" enctype="multipart/form-data"
                                  action="{{route('receipt.import.upload')}}">
                                @csrf
                                <div class="mb-2">
                                    <label>{{__('receipt')}} <small>(.pdf)</small></label>
                                    <input id="file" type="file" class="form-control" name="file" required>
                                </div>

                                <button type="submit" class="btn btn-primary">{{__('upload')}}</button>
                            </form>
                        </div>
                        <div class="col">
                            <h3>{{__('automatic-method')}}</h3>
                            <small>{{__('receipt.automatic.explain')}}</small>
                            <hr/>
                            <p>{{__('recipient')}}: <b>{{$ebonKey ?? ''}}@reweebon.k118.de</b></p>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">{{__('close')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col" style="font-weight: bold;">
            <div class="card mb-2">
                <div class="card-body" style="text-align: center;">
                    <div class="row">
                        <div class="col-md-3">
                            <span class="color-highlight" style="font-size: 40px;">
                                {{auth()->user()->reweReceipts->count()}}
                            </span>
                            <br>
                            <small><b>{{__('receipts.count')}}</b></small>
                        </div>
                        <div class="col-md-3">
                            <span class="color-highlight" style="font-size: 40px;">
                                {{$mostUsedPaymentMethod}}
                            </span>
                            <br>
                            <small><b>{{__('most-used-payment-methods')}}</b></small>
                        </div>
                        <div class="col-md-3">
                                <span class="color-highlight" style="font-size: 40px;">
                                    {{ number_format(auth()->user()->reweReceipts->avg('total'), 2, ',', '.') }}
                                    <small>€</small>
                                    <i class="mdi mdi-trending-up"
                                       style="font-size: 25px; color: rgb(255, 99, 132)"></i>
                                </span>
                            <br>
                            <small><b>{{__('avg-receipt-price')}}</b></small>
                        </div>
                        <div class="col-md-3">
                            <span class="color-highlight" style="font-size: 40px;">
                                {{ number_format(auth()->user()->reweReceipts->sum('total'), 2, ',', '.') }}
                                <small>€</small>
                            </span>
                            <br>
                            <small><b>{{__('payed-total')}}</b></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            @include('rewe_ebon.charts.payment')
        </div>
        <div class="col-md-4">
            @include('rewe_ebon.charts.markets')
        </div>
        <div class="col-md-4">
            @include('rewe_ebon.charts.vegetarian')
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('product-favourite')}}</h5>
                    <table class="table" id="lieblingsprodukte">
                        <thead>
                            <tr>
                                <th>{{__('receipts.product')}}</th>
                                <th>{{__('receipts.amount')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($favouriteProducts as $product)
                                <tr>
                                    <td>
                                        <a href="{{route('rewe.product', ['id' => $product->id])}}">
                                            {{$product->name}}
                                        </a>
                                    </td>
                                    <td data-order="{{$product->cnt}}">{{$product->cnt}}x</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <script>
                        $('#lieblingsprodukte').DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/German.json"
                            },
                            "order": [[1, 'desc']],
                            "pageLength": 5,
                            "lengthMenu": [5, 10, 25, 50, 75, 100]
                        });
                    </script>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('product.prediction')}}</h5>
                    <table class="table" id="kaufvorhersage">
                        <thead>
                            <tr>
                                <th>{{__('receipts.product')}}</th>
                                <th>{{__('receipts.last_bought')}}</th>
                                <th>{{__('product.next-buy')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forecast as $product)
                                <tr>
                                    <td>
                                        <a href="{{route('rewe.product', ['id' => $product->id])}}">
                                            {{$product->name}}
                                        </a>
                                    </td>
                                    <td>{{Carbon\Carbon::parse($product->lastTS)->diffForHumans()}}</td>
                                    <td>{{Carbon\Carbon::parse($product->nextTS)->diffForHumans()}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <script>
                        $('#kaufvorhersage').DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/German.json"
                            },
                            "order": [[1, 'asc']],
                            "pageLength": 5,
                            "lengthMenu": [5, 10, 25, 50, 75, 100]
                        });
                    </script>
                    <small>{{__('product.prediction.text')}}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{__('receipt')}}</h5>
                    <table class="table" id="kassenzettel">
                        <thead>
                            <tr>
                                <th class="no-sort"></th>
                                <th>{{__('time')}}</th>
                                <th class="no-sort">{{__('receipts.market')}}</th>
                                <th class="no-sort">{{__('cash-register')}}</th>
                                <th class="no-sort">{{__('receipts.payment_method')}}</th>
                                <th>{{__('receipts.price_total')}}</th>
                                <th class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach(auth()->user()->reweReceipts->sortByDesc('timestamp_bon') as $receipt)
                                <tr>
                                    <td>
                                        @isset($receipt->shop->brand?->vector_logo)
                                            <img src="data:image/svg+xml;base64,{{base64_encode($receipt->shop->brand->vector_logo)}}"
                                                 style="max-height: 25px; max-width: 100px; min-height: 15px;"/>
                                        @endisset
                                    </td>
                                    <td data-order="{{$receipt->timestamp_bon}}">
                                        {{$receipt->timestamp_bon->format('d.m.Y H:i')}}
                                    </td>
                                    <td>
                                        <a href="{{route('rewe.shop', ['id' => $receipt->shop->id])}}">
                                            Markt {{$receipt->shop->id}}<br/>
                                            <small>in {{$receipt->shop->zip}} {{$receipt->shop->city}}</small>
                                        </a>
                                    </td>
                                    <td>{{$receipt->cashregister_nr}}</td>
                                    <td>{{$receipt->paymentmethod}}</td>
                                    <td data-order="{{$receipt->total}}">
                                        {{number_format($receipt->total, 2, ",", ".")}} €
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('rewe_receipt', [$receipt->id]) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            {{__('details')}}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <script>
                        $('#kassenzettel').DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/German.json"
                            },
                            "order": [[1, 'desc']],
                            "pageLength": 5,
                            "lengthMenu": [5, 10, 25, 50, 75, 100],
                            columnDefs: [
                                {targets: 'no-sort', orderable: false}
                            ]
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            @include('rewe_ebon.charts.daytime')
        </div>

        <div class="col-md-12">
            @include('rewe_ebon.charts.spent-month')
        </div>
    </div>

    <!--<div class="row">
        <div class="col-md-12">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">Einkaufswert pro Einkauf</h5>
                    <p>Dieser Statistik steht in Kürze wieder zur Verfügung.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">Ausgaben nach Kategorie</h5>
                    <p>Dieser Statistik steht in Kürze wieder zur Verfügung.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">Top Kategorien (nach Anzahl Produkte)</h5>
                    <p>Dieser Statistik steht in Kürze wieder zur Verfügung.</p>
                </div>
            </div>
        </div>
    </div>-->

    <div class="row">
        <div class="col-md-6">
            @include('rewe_ebon.charts.category-spent')
        </div>
        <div class="col-md-6">
            @include('rewe_ebon.charts.category-amount')
        </div>
    </div>
@endsection
