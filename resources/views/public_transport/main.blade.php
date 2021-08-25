@extends('layout.app')

@section('before-title')
    <button class="btn btn-primary btn-sm float-right" data-toggle="modal"
            data-target="#modal-add-pt-card">
        <i class="far fa-credit-card"></i> {{__('pt.card.add')}}
    </button>
@endsection

@section('title', __('pt.card.heading'))

@section('content')
    @if(auth()->user()->publicTransportCards->where('isValid', true)->count() == 0)
        @include('public_transport.cards.middle-no-card')
    @else
        @foreach(auth()->user()->publicTransportCards->where('isValid', true) as $card)
            <div class="row">
                <div class="col-md-3">
                    @include('public_transport.cards.your-cards')
                </div>
                <div class="col-md-9">
                    @include('public_transport.cards.your-journeys')
                </div>
            </div>
            @include('public_transport.cards.modal-cost-calculation')
            <hr/>
        @endforeach
    @endif

    @include('public_transport.cards.modal-add-card')
    @include('public_transport.cards.modal-add-journey')
    @include('public_transport.cards.modal-add-complaint')
@endsection
