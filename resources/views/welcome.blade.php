@extends('layout.cover')

@section('content')
    <main role="main" class="inner cover">
        <h1 class="cover-heading">KStats - Statistiken</h1>
        <p class="lead">Erhalte Statistiken über deinen Musikgeschmack oder dein Einkaufsverhalten.</p>
        <p class="lead">
            <a href="{{ route('home') }}" class="btn btn-lg btn-secondary">Zu den Statistiken</a>
        </p>
        <hr />
        <p class="lead"><small>KStats wurde im Mai 2020 komplett neu und in <a href="https://github.com/MrKrisKrisu/KStats" target="github">OpenSource</a> programmiert!
            <br />Es sind noch nicht alle Features komplett wieder implementiert.</small></p>
    </main>
@endsection
