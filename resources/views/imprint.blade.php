@extends('layout.cover')

@section('content')
    <main role="main" class="inner cover">
        <h1>Impressum</h1>
        <p>Angaben gemäß § 5 TMG</p>
        <p>{{ config('app.imprint.name') }} <br>
            {{ config('app.imprint.address') }}<br>
            {{ config('app.imprint.city') }} <br>
        </p>
        <p><strong>Kontakt:</strong> <br>
            Telefon: {{ config('app.imprint.phone') }}<br>
            Fax: {{ config('app.imprint.fax') }}<br>
            E-Mail: {{ config('app.imprint.email') }}<br/></p>
        <br>
        <p>

            Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation
            per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem
            Zugriff durch Dritte ist nicht möglich. <br>
            Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten durch
            Dritte zur Übersendung von nicht ausdrücklich angeforderter Werbung und
            Informationsmaterialien wird hiermit ausdrücklich widersprochen. Die Betreiber der
            Seiten behalten sich ausdrücklich rechtliche Schritte im Falle der unverlangten
            Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor.<br>
        </p><br>
        Website Impressum von <a
                href="https://www.impressum-generator.de">impressum-generator.de</a>

    </main>
@endsection
