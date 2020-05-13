@extends('layout.cover')

@section('content')
    <main role="main" class="inner cover">
        <h1>Impressum</h1>
        <p>Angaben gemäß § 5 TMG</p>
        <p>{{ env('IMPRINT_NAME') }} <br>
            {{ env('IMPRINT_ADDRESS') }}<br>
            {{ env('IMPRINT_CITY') }} <br>
        </p>
        <p><strong>Kontakt:</strong> <br>
            Telefon: {{ env('IMPRINT_PHONE') }}<br>
            Fax: {{ env('IMPRINT_FAX') }}<br>
            E-Mail: {{ env('IMPRINT_EMAIL') }}<br/></p>
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
