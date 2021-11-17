@extends('layout.app')

@section('before-container')
    <section class="p-5 mb-4 bg-light text-center"
             style="background: url('{{url('/img/bg_cover.png')}}') 0 35%; background-size: 100%;">
        <div class="container">
            <h1 class="jumbotron-heading" style="color: #fff;">Impressum</h1>
            <p class="lead" style="color: #fff;"></p>
        </div>
    </section>
@endsection

@section('content')
    <main role="main" class="inner cover">
        <h2>Verantwortliche Stelle</h2>
        <b>Angaben gemäß § 5 TMG</b>
        <p>{{ config('app.imprint.name') }}<br/>
            {{ config('app.imprint.address') }}<br/>
            {{ config('app.imprint.city') }}<br/>
        </p>
        <p><strong>Kontakt:</strong><br/>
            Telefon: {{ config('app.imprint.phone') }}<br/>
            Fax: {{ config('app.imprint.fax') }}<br/>
            E-Mail: {{ config('app.imprint.email') }}<br/>
        </p>
    </main>
@endsection
