@extends('layout.app')

@section('title') Freundschaftsfunktion aktivieren @endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <p class="fs-bold text-danger">Mit dem Klick auf den folgenden Button bestätigst du, dass
                        du dich mit
                        anderen KStats-Usern verbinden möchtest. Dir ist bewusst, dass dein Nutzername für andere Nutzer
                        sichtbar wird.</p>
                    <form method="POST" action="{{route('friendships.module.activate')}}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            Freundschaftsfunktion aktivieren
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
