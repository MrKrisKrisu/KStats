@extends('layout.app')

@section('title') Einkaufsstatistik @endsection

@section('content')
    <div class="row">
        @if($receipts->count() > 0)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            @foreach($receipts as $receipt)
                                <tr>
                                    <td>{{$receipt->id}}</td>
                                    <td>{{$receipt->timestamp->format('d.m.Y H:i')}}</td>

                                </tr>
                            @endforeach
                        </table>
                        {{$receipts->links()}}
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Kassenzettel automatisch hinzufügen</h2>

                    <hr />
                    <h3>Unterstützte Supermärkte</h3>
                    <p class="text-muted">KStats kann zur Zeit die digitalen Kassenzettel von folgenden Supermärkten automatisch verarbeiten:</p>
                    <img style="height: 50px;"
                         src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Logo_REWE.svg/320px-Logo_REWE.svg.png"/>
                    <img style="height: 50px;"
                         src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Lidl-Logo.svg/240px-Lidl-Logo.svg.png"/>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Kassenzettel manuell hinzufügen</h2>

                </div>
            </div>
        </div>
    </div>
@endsection
