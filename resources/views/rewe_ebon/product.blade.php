@extends('layout.app')

@section('title'){{$product->name}} bei REWE @endsection

@section('content')
    <div class="row">
        <div class="col-md-12" style="font-weight: bold;">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    <div class="row">
                        <div class="col">
                            <span class="color-highlight" style="font-size: 40px;">
                                @isset($mainStats->weight)
                                    {{$mainStats->weight}} kg
                                @else
                                    {{$mainStats->amount}} x
                                @endisset
                            </span>
                            <br/>
                            <small><b>hast du bereits gekauft</b></small>
                        </div>

                        <div class="col">
                            <span class="color-highlight" style="font-size: 40px;">
                                {{number_format($mainStats->single_price, 2, ',', '.')}} €
                            </span>
                            <br/>
                            <small><b>durchschnittlicher Einkaufspreis</b></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Markt</th>
                                <th>Zeitpunkt</th>
                                <th>Anzahl / Gewicht</th>
                                <th>Einzelpreis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $position)
                                <tr>
                                    <td>
                                        Markt {{$position->bon->shop->id}}<br/>
                                        <small>{{$position->bon->shop->zip}} {{$position->bon->shop->city}}</small>
                                    </td>
                                    <td>{{$position->receipt->timestamp_bon->format('d.m.Y H:i')}}</td>
                                    <td>
                                        @isset($position->weight)
                                            {{$position->weight}} kg
                                        @else
                                            {{$position->amount}}x
                                        @endisset
                                    </td>
                                    <td>{{number_format($position->single_price, 2, ',', '.')}} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$history->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection