@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Willkommen im neuen KStats</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p>Optisch kaum anders, aber im inner'n hat sich viel getan. Dies ist aktuell erst eine
                        "Vorab-Version", einige Features und Statistiken, die du vielleicht kennst sind aktuell noch
                            nicht Verfügbar, kommen aber bald zurück!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
