@extends('layout.app')

@section('before-container')
    <section class="jumbotron text-center"
             style="background: url('{{url('/img/bg_cover.png')}}') 0% 35%; background-size: 100%; border-radius: 0;">
        <div class="container">
            <h1 class="jumbotron-heading" style="color: #fff;">Datenschutzerklärung</h1>
            <p class="lead" style="color: #fff;">Stand: 7. November 2020</p>
        </div>
    </section>
@endsection

@section('content')
    <main role="main" class="inner cover">
        <h2>Einleitung</h2>
        <p>Mit der folgenden Datenschutzerklärung möchten wir Sie darüber aufklären, welche Arten Ihrer
            personenbezogenen Daten (nachfolgend auch kurz als "Daten“ bezeichnet) wir zu welchen Zwecken und in welchem
            Umfang verarbeiten. Die Datenschutzerklärung gilt für alle von uns durchgeführten Verarbeitungen
            personenbezogener Daten, sowohl im Rahmen der Erbringung unserer Leistungen als auch insbesondere auf
            unseren Webseiten, in mobilen Applikationen sowie innerhalb externer Onlinepräsenzen, wie z.B. unserer
            Social-Media-Profile (nachfolgend zusammenfassend bezeichnet als "Onlineangebot“).</p>
        <p>Die verwendeten Begriffe sind nicht geschlechtsspezifisch.</p>
        <h2>Inhaltsübersicht</h2>
        <ul class="index">
            <li><a class="index-link" href="#m14">Einleitung</a></li>
            <li><a class="index-link" href="#m3">Verantwortlicher</a></li>
            <li><a class="index-link" href="#mOverview">Übersicht der Verarbeitungen</a></li>
            <li><a class="index-link" href="#m13">Maßgebliche Rechtsgrundlagen</a></li>
            <li><a class="index-link" href="#m27">Sicherheitsmaßnahmen</a></li>
            <li><a class="index-link" href="#m25">Übermittlung und Offenbarung von personenbezogenen Daten</a></li>
            <li><a class="index-link" href="#m24">Datenverarbeitung in Drittländern</a></li>
            <li><a class="index-link" href="#m134">Einsatz von Cookies</a></li>
            <li><a class="index-link" href="#m225">Bereitstellung des Onlineangebotes und Webhosting</a></li>
            <li><a class="index-link" href="#m367">Registrierung, Anmeldung und Nutzerkonto</a></li>
            <li><a class="index-link" href="#m451">Single-Sign-On-Anmeldung</a></li>
            <li><a class="index-link" href="#m263">Webanalyse, Monitoring und Optimierung</a></li>
            <li><a class="index-link" href="#m12">Löschung von Daten</a></li>
            <li><a class="index-link" href="#m15">Änderung und Aktualisierung der Datenschutzerklärung</a></li>
            <li><a class="index-link" href="#m10">Rechte der betroffenen Personen</a></li>
        </ul>
        <h2 id="m3">Verantwortlicher</h2>
        <p>
            {{ config('app.imprint.name') }} <br/>
            {{ config('app.imprint.address') }}<br/>
            {{ config('app.imprint.city') }} <br/>
        </p>
        <p><strong>E-Mail-Adresse:</strong> {{ config('app.imprint.email') }}</p>
        <p>
            <strong>Telefon:</strong> {{ config('app.imprint.phone') }} <br/>
            <strong>Telefax:</strong> {{ config('app.imprint.fax') }}
        </p>

        <h2 id="mOverview">Übersicht der Verarbeitungen</h2>
        <p>Die nachfolgende Übersicht fasst die Arten der verarbeiteten Daten und die Zwecke ihrer Verarbeitung zusammen
            und verweist auf die betroffenen Personen.</p>

        <h3>Arten der verarbeiteten Daten</h3>
        <ul>
            <li>Bestandsdaten (z.B. Namen, Adressen).</li>
            <li>Inhaltsdaten (z.B. Eingaben in Onlineformularen).</li>
            <li>Kontaktdaten (z.B. E-Mail, Telefonnummern).</li>
            <li>Meta-/Kommunikationsdaten (z.B. Geräte-Informationen, IP-Adressen).</li>
            <li>Nutzungsdaten (z.B. besuchte Webseiten, Interesse an Inhalten, Zugriffszeiten).</li>
        </ul>

        <p>Je nachdem, welche Statistiken vom Benutzer aktiviert sind, werden noch folgende Daten verarbeitet:</p>
        <ul>
            <li>Spotify: Gehörte Tracks inkl. Zeitstempel und Metadaten</li>
            <li>REWE: Inhalte des eBon</li>
            <li>Twitter: Öffentliche Informationen, Tweets, Likes, Follower</li>
        </ul>
        <h3>Kategorien betroffener Personen</h3>
        <ul>
            <li>Nutzer (z.B. Webseitenbesucher, Nutzer von Onlinediensten).</li>
        </ul>
        <h3>Zwecke der Verarbeitung</h3>
        <ul>
            <li>Anmeldeverfahren.</li>
            <li>Konversionsmessung (Messung der Effektivität von Marketingmaßnahmen).</li>
            <li>Profiling (Erstellen von Nutzerprofilen).</li>
            <li>Reichweitenmessung (z.B. Zugriffsstatistiken, Erkennung wiederkehrender Besucher).</li>
            <li>Sicherheitsmaßnahmen.</li>
            <li>Tracking (z.B. interessens-/verhaltensbezogenes Profiling, Nutzung von Cookies).</li>
            <li>Erbringung vertragliche Leistungen und Kundenservice.</li>
            <li>Verwaltung und Beantwortung von Anfragen.</li>
            <li>Statistikenerstellung bei vom User erstellten Services</li>
        </ul>
        <h3 id="m13">Maßgebliche Rechtsgrundlagen</h3>
        <p>Im Folgenden teilen wir die Rechtsgrundlagen der Datenschutzgrundverordnung (DSGVO), auf deren Basis wir die
            personenbezogenen Daten verarbeiten, mit. Bitte beachten Sie, dass zusätzlich zu den Regelungen der DSGVO
            die nationalen Datenschutzvorgaben in Ihrem bzw. unserem Wohn- und Sitzland gelten können. Sollten ferner im
            Einzelfall speziellere Rechtsgrundlagen maßgeblich sein, teilen wir Ihnen diese in der Datenschutzerklärung
            mit.</p>
        <ul>
            <li><strong>Einwilligung (Art. 6 Abs. 1 S. 1 lit. a. DSGVO)</strong> - Die betroffene Person hat ihre
                Einwilligung in die Verarbeitung der sie betreffenden personenbezogenen Daten für einen spezifischen
                Zweck oder mehrere bestimmte Zwecke gegeben.
            </li>
            <li><strong>Vertragserfüllung und vorvertragliche Anfragen (Art. 6 Abs. 1 S. 1 lit. b. DSGVO)</strong> - Die
                Verarbeitung ist für die Erfüllung eines Vertrags, dessen Vertragspartei die betroffene Person ist, oder
                zur Durchführung vorvertraglicher Maßnahmen erforderlich, die auf Anfrage der betroffenen Person
                erfolgen.
            </li>
            <li><strong>Berechtigte Interessen (Art. 6 Abs. 1 S. 1 lit. f. DSGVO)</strong> - Die Verarbeitung ist zur
                Wahrung der berechtigten Interessen des Verantwortlichen oder eines Dritten erforderlich, sofern nicht
                die Interessen oder Grundrechte und Grundfreiheiten der betroffenen Person, die den Schutz
                personenbezogener Daten erfordern, überwiegen.
            </li>
        </ul>
        <p><strong>Nationale Datenschutzregelungen in Deutschland</strong>: Zusätzlich zu den Datenschutzregelungen der
            Datenschutz-Grundverordnung gelten nationale Regelungen zum Datenschutz in Deutschland. Hierzu gehört
            insbesondere das Gesetz zum Schutz vor Missbrauch personenbezogener Daten bei der Datenverarbeitung
            (Bundesdatenschutzgesetz – BDSG). Das BDSG enthält insbesondere Spezialregelungen zum Recht auf Auskunft,
            zum Recht auf Löschung, zum Widerspruchsrecht, zur Verarbeitung besonderer Kategorien personenbezogener
            Daten, zur Verarbeitung für andere Zwecke und zur Übermittlung sowie automatisierten Entscheidungsfindung im
            Einzelfall einschließlich Profiling. Des Weiteren regelt es die Datenverarbeitung für Zwecke des
            Beschäftigungsverhältnisses (§ 26 BDSG), insbesondere im Hinblick auf die Begründung, Durchführung oder
            Beendigung von Beschäftigungsverhältnissen sowie die Einwilligung von Beschäftigten. Ferner können
            Landesdatenschutzgesetze der einzelnen Bundesländer zur Anwendung gelangen.</p>
        <h2 id="m27">Sicherheitsmaßnahmen</h2>
        <p>Wir treffen nach Maßgabe der gesetzlichen Vorgaben unter Berücksichtigung des Stands der Technik, der
            Implementierungskosten und der Art, des Umfangs, der Umstände und der Zwecke der Verarbeitung sowie der
            unterschiedlichen Eintrittswahrscheinlichkeiten und des Ausmaßes der Bedrohung der Rechte und Freiheiten
            natürlicher Personen geeignete technische und organisatorische Maßnahmen, um ein dem Risiko angemessenes
            Schutzniveau zu gewährleisten.</p>
        <p>Zu den Maßnahmen gehören insbesondere die Sicherung der Vertraulichkeit, Integrität und Verfügbarkeit von
            Daten durch Kontrolle des physischen und elektronischen Zugangs zu den Daten als auch des sie betreffenden
            Zugriffs, der Eingabe, der Weitergabe, der Sicherung der Verfügbarkeit und ihrer Trennung. Des Weiteren
            haben wir Verfahren eingerichtet, die eine Wahrnehmung von Betroffenenrechten, die Löschung von Daten und
            Reaktionen auf die Gefährdung der Daten gewährleisten. Ferner berücksichtigen wir den Schutz
            personenbezogener Daten bereits bei der Entwicklung bzw. Auswahl von Hardware, Software sowie Verfahren
            entsprechend dem Prinzip des Datenschutzes, durch Technikgestaltung und durch datenschutzfreundliche
            Voreinstellungen.</p>
        <p><strong>Kürzung der IP-Adresse</strong>: Sofern es uns möglich ist oder eine Speicherung der IP-Adresse nicht
            erforderlich ist, kürzen wir oder lassen Ihre IP-Adresse kürzen. Im Fall der Kürzung der IP-Adresse, auch
            als "IP-Masking" bezeichnet, wird das letzte Oktett, d.h., die letzten beiden Zahlen einer IP-Adresse,
            gelöscht (die IP-Adresse ist in diesem Kontext eine einem Internetanschluss durch den
            Online-Zugangs-Provider individuell zugeordnete Kennung). Mit der Kürzung der IP-Adresse soll die
            Identifizierung einer Person anhand ihrer IP-Adresse verhindert oder wesentlich erschwert werden.</p>
        <p><strong>SSL-Verschlüsselung (https)</strong>: Um Ihre via unser Online-Angebot übermittelten Daten zu
            schützen, nutzen wir eine SSL-Verschlüsselung. Sie erkennen derart verschlüsselte Verbindungen an dem Präfix
            https:// in der Adresszeile Ihres Browsers.</p>
        <h2 id="m25">Übermittlung und Offenbarung von personenbezogenen Daten</h2>
        <p>Im Rahmen unserer Verarbeitung von personenbezogenen Daten kommt es vor, dass die Daten an andere Stellen,
            Unternehmen, rechtlich selbstständige Organisationseinheiten oder Personen übermittelt oder sie ihnen
            gegenüber offengelegt werden. Zu den Empfängern dieser Daten können z.B. Zahlungsinstitute im Rahmen von
            Zahlungsvorgängen, mit IT-Aufgaben beauftragte Dienstleister oder Anbieter von Diensten und Inhalten, die in
            eine Webseite eingebunden werden, gehören. In solchen Fall beachten wir die gesetzlichen Vorgaben und
            schließen insbesondere entsprechende Verträge bzw. Vereinbarungen, die dem Schutz Ihrer Daten dienen, mit
            den Empfängern Ihrer Daten ab.</p>
        <h2 id="m24">Datenverarbeitung in Drittländern</h2>
        <p>Sofern wir Daten in einem Drittland (d.h., außerhalb der Europäischen Union (EU), des Europäischen
            Wirtschaftsraums (EWR)) verarbeiten oder die Verarbeitung im Rahmen der Inanspruchnahme von Diensten Dritter
            oder der Offenlegung bzw. Übermittlung von Daten an andere Personen, Stellen oder Unternehmen stattfindet,
            erfolgt dies nur im Einklang mit den gesetzlichen Vorgaben. </p>
        <p>Vorbehaltlich ausdrücklicher Einwilligung oder vertraglich oder gesetzlich erforderlicher Übermittlung
            verarbeiten oder lassen wir die Daten nur in Drittländern mit einem anerkannten Datenschutzniveau,
            vertraglichen Verpflichtung durch sogenannte Standardschutzklauseln der EU-Kommission, beim Vorliegen von
            Zertifizierungen oder verbindlicher internen Datenschutzvorschriften verarbeiten (Art. 44 bis 49 DSGVO,
            Informationsseite der EU-Kommission: <a
                    href="https://ec.europa.eu/info/law/law-topic/data-protection/international-dimension-data-protection_de"
                    target="_blank">https://ec.europa.eu/info/law/law-topic/data-protection/international-dimension-data-protection_de</a>
            ).</p>
        <h2 id="m134">Einsatz von Cookies</h2>
        <p>Cookies sind Textdateien, die Daten von besuchten Websites oder Domains enthalten und von einem Browser auf
            dem Computer des Benutzers gespeichert werden. Ein Cookie dient in erster Linie dazu, die Informationen über
            einen Benutzer während oder nach seinem Besuch innerhalb eines Onlineangebotes zu speichern. Zu den
            gespeicherten Angaben können z.B. die Spracheinstellungen auf einer Webseite, der Loginstatus, ein Warenkorb
            oder die Stelle, an der ein Video geschaut wurde, gehören. Zu dem Begriff der Cookies zählen wir ferner
            andere Technologien, die die gleichen Funktionen wie Cookies erfüllen (z.B., wenn Angaben der Nutzer anhand
            pseudonymer Onlinekennzeichnungen gespeichert werden, auch als "Nutzer-IDs" bezeichnet)</p>
        <p><strong>Die folgenden Cookie-Typen und Funktionen werden unterschieden:</strong></p>
        <ul>
            <li><strong>Temporäre Cookies (auch: Session- oder Sitzungs-Cookies):</strong>&nbsp;Temporäre Cookies werden
                spätestens gelöscht, nachdem ein Nutzer ein Online-Angebot verlassen und seinen Browser geschlossen hat.
            </li>
            <li><strong>Permanente Cookies:</strong>&nbsp;Permanente Cookies bleiben auch nach dem Schließen des
                Browsers gespeichert. So kann beispielsweise der Login-Status gespeichert oder bevorzugte Inhalte direkt
                angezeigt werden, wenn der Nutzer eine Website erneut besucht. Ebenso können die Interessen von Nutzern,
                die zur Reichweitenmessung oder zu Marketingzwecken verwendet werden, in einem solchen Cookie
                gespeichert werden.
            </li>
            <li><strong>First-Party-Cookies:</strong>&nbsp;First-Party-Cookies werden von uns selbst gesetzt.</li>
            <li><strong>Third-Party-Cookies (auch: Drittanbieter-Cookies)</strong>: Drittanbieter-Cookies werden
                hauptsächlich von Werbetreibenden (sog. Dritten) verwendet, um Benutzerinformationen zu verarbeiten.
            </li>
            <li><strong>Notwendige (auch: essentielle oder unbedingt erforderliche) Cookies:</strong> Cookies können zum
                einen für den Betrieb einer Webseite unbedingt erforderlich sein (z.B. um Logins oder andere
                Nutzereingaben zu speichern oder aus Gründen der Sicherheit).
            </li>
            <li><strong>Statistik-, Marketing- und Personalisierungs-Cookies</strong>: Ferner werden Cookies im
                Regelfall auch im Rahmen der Reichweitenmessung eingesetzt sowie dann, wenn die Interessen eines Nutzers
                oder sein Verhalten (z.B. Betrachten bestimmter Inhalte, Nutzen von Funktionen etc.) auf einzelnen
                Webseiten in einem Nutzerprofil gespeichert werden. Solche Profile dienen dazu, den Nutzern z.B. Inhalte
                anzuzeigen, die ihren potentiellen Interessen entsprechen. Dieses Verfahren wird auch als "Tracking",
                d.h., Nachverfolgung der potentiellen Interessen der Nutzer bezeichnet. Soweit wir Cookies oder
                "Tracking"-Technologien einsetzen, informieren wir Sie gesondert in unserer Datenschutzerklärung oder im
                Rahmen der Einholung einer Einwilligung.
            </li>
        </ul>
        <p><strong>Hinweise zu Rechtsgrundlagen: </strong> Auf welcher Rechtsgrundlage wir Ihre personenbezogenen Daten
            mit Hilfe von Cookies verarbeiten, hängt davon ab, ob wir Sie um eine Einwilligung bitten. Falls dies
            zutrifft und Sie in die Nutzung von Cookies einwilligen, ist die Rechtsgrundlage der Verarbeitung Ihrer
            Daten die erklärte Einwilligung. Andernfalls werden die mithilfe von Cookies verarbeiteten Daten auf
            Grundlage unserer berechtigten Interessen (z.B. an einem betriebswirtschaftlichen Betrieb unseres
            Onlineangebotes und dessen Verbesserung) verarbeitet oder, wenn der Einsatz von Cookies erforderlich ist, um
            unsere vertraglichen Verpflichtungen zu erfüllen.</p>
        <p><strong>Speicherdauer: </strong>Sofern wir Ihnen keine expliziten Angaben zur Speicherdauer von permanenten
            Cookies mitteilen (z. B. im Rahmen eines sog. Cookie-Opt-Ins), gehen Sie bitte davon aus, dass die
            Speicherdauer bis zu zwei Jahre betragen kann.</p>
        <p><strong>Allgemeine Hinweise zum Widerruf und Widerspruch (Opt-Out): </strong> Abhängig davon, ob die
            Verarbeitung auf Grundlage einer Einwilligung oder gesetzlichen Erlaubnis erfolgt, haben Sie jederzeit die
            Möglichkeit, eine erteilte Einwilligung zu widerrufen oder der Verarbeitung Ihrer Daten durch
            Cookie-Technologien zu widersprechen (zusammenfassend als "Opt-Out" bezeichnet). Sie können Ihren
            Widerspruch zunächst mittels der Einstellungen Ihres Browsers erklären, z.B., indem Sie die Nutzung von
            Cookies deaktivieren (wobei hierdurch auch die Funktionsfähigkeit unseres Onlineangebotes eingeschränkt
            werden kann). Ein Widerspruch gegen den Einsatz von Cookies zu Zwecken des Onlinemarketings kann auch
            mittels einer Vielzahl von Diensten, vor allem im Fall des Trackings, über die Webseiten <a
                    href="https://optout.aboutads.info" target="_blank">https://optout.aboutads.info</a> und <a
                    href="https://www.youronlinechoices.com/" target="_blank">https://www.youronlinechoices.com/</a>
            erklärt werden. Daneben können Sie weitere Widerspruchshinweise im Rahmen der Angaben zu den eingesetzten
            Dienstleistern und Cookies erhalten.</p>
        <p><strong>Verarbeitung von Cookie-Daten auf Grundlage einer Einwilligung</strong>: Bevor wir Daten im Rahmen
            der Nutzung von Cookies verarbeiten oder verarbeiten lassen, bitten wir die Nutzer um eine jederzeit
            widerrufbare Einwilligung. Bevor die Einwilligung nicht ausgesprochen wurde, werden allenfalls Cookies
            eingesetzt, die für den Betrieb unseres Onlineangebotes unbedingt erforderlich sind.</p>
        <ul class="m-elements">
            <li><strong>Verarbeitete Datenarten:</strong> Nutzungsdaten (z.B. besuchte Webseiten, Interesse an Inhalten,
                Zugriffszeiten), Meta-/Kommunikationsdaten (z.B. Geräte-Informationen, IP-Adressen).
            </li>
            <li><strong>Betroffene Personen:</strong> Nutzer (z.B. Webseitenbesucher, Nutzer von Onlinediensten).</li>
            <li><strong>Rechtsgrundlagen:</strong> Einwilligung (Art. 6 Abs. 1 S. 1 lit. a. DSGVO), Berechtigte
                Interessen (Art. 6 Abs. 1 S. 1 lit. f. DSGVO).
            </li>
        </ul>
        <h2 id="m225">Bereitstellung des Onlineangebotes und Webhosting</h2>
        <p>Um unser Onlineangebot sicher und effizient bereitstellen zu können, nehmen wir die Leistungen von einem oder
            mehreren Webhosting-Anbietern in Anspruch, von deren Servern (bzw. von ihnen verwalteten Servern) das
            Onlineangebot abgerufen werden kann. Zu diesen Zwecken können wir Infrastruktur- und
            Plattformdienstleistungen, Rechenkapazität, Speicherplatz und Datenbankdienste sowie Sicherheitsleistungen
            und technische Wartungsleistungen in Anspruch nehmen.</p>
        <p>Zu den im Rahmen der Bereitstellung des Hostingangebotes verarbeiteten Daten können alle die Nutzer unseres
            Onlineangebotes betreffenden Angaben gehören, die im Rahmen der Nutzung und der Kommunikation anfallen.
            Hierzu gehören regelmäßig die IP-Adresse, die notwendig ist, um die Inhalte von Onlineangeboten an Browser
            ausliefern zu können, und alle innerhalb unseres Onlineangebotes oder von Webseiten getätigten Eingaben.</p>
        <p><strong>Erhebung von Zugriffsdaten und Logfiles</strong>: Wir selbst (bzw. unser Webhostinganbieter) erheben
            Daten zu jedem Zugriff auf den Server (sogenannte Serverlogfiles). Zu den Serverlogfiles können die Adresse
            und Name der abgerufenen Webseiten und Dateien, Datum und Uhrzeit des Abrufs, übertragene Datenmengen,
            Meldung über erfolgreichen Abruf, Browsertyp nebst Version, das Betriebssystem des Nutzers, Referrer URL
            (die zuvor besuchte Seite) und im Regelfall IP-Adressen und der anfragende Provider gehören.</p>
        <p>Die Serverlogfiles können zum einen zu Zwecken der Sicherheit eingesetzt werden, z.B., um eine Überlastung
            der Server zu vermeiden (insbesondere im Fall von missbräuchlichen Angriffen, sogenannten DDoS-Attacken) und
            zum anderen, um die Auslastung der Server und ihre Stabilität sicherzustellen.</p>
        <ul class="m-elements">
            <li><strong>Verarbeitete Datenarten:</strong> Inhaltsdaten (z.B. Eingaben in Onlineformularen),
                Nutzungsdaten (z.B. besuchte Webseiten, Interesse an Inhalten, Zugriffszeiten),
                Meta-/Kommunikationsdaten (z.B. Geräte-Informationen, IP-Adressen).
            </li>
            <li><strong>Betroffene Personen:</strong> Nutzer (z.B. Webseitenbesucher, Nutzer von Onlinediensten).</li>
            <li><strong>Rechtsgrundlagen:</strong> Berechtigte Interessen (Art. 6 Abs. 1 S. 1 lit. f. DSGVO).</li>
        </ul>
        <h2 id="m367">Registrierung, Anmeldung und Nutzerkonto</h2>
        <p>Nutzer können ein Nutzerkonto anlegen. Im Rahmen der Registrierung werden den Nutzern die erforderlichen
            Pflichtangaben mitgeteilt und zu Zwecken der Bereitstellung des Nutzerkontos auf Grundlage vertraglicher
            Pflichterfüllung verarbeitet. Zu den verarbeiteten Daten gehören insbesondere die Login-Informationen (Name,
            Passwort sowie eine E-Mail-Adresse). Die im Rahmen der Registrierung eingegebenen Daten werden für die
            Zwecke der Nutzung des Nutzerkontos und dessen Zwecks verwendet. </p>
        <p>Die Nutzer können über Vorgänge, die für deren Nutzerkonto relevant sind, wie z.B. technische Änderungen, per
            E-Mail informiert werden. Wenn Nutzer ihr Nutzerkonto gekündigt haben, werden deren Daten im Hinblick auf
            das Nutzerkonto, vorbehaltlich einer gesetzlichen Aufbewahrungspflicht, gelöscht. Es obliegt den Nutzern,
            ihre Daten bei erfolgter Kündigung vor dem Vertragsende zu sichern. Wir sind berechtigt, sämtliche während
            der Vertragsdauer gespeicherte Daten des Nutzers unwiederbringlich zu löschen.</p>
        <p>Im Rahmen der Inanspruchnahme unserer Registrierungs- und Anmeldefunktionen sowie der Nutzung des
            Nutzerkontos speichern wir die IP-Adresse und den Zeitpunkt der jeweiligen Nutzerhandlung. Die Speicherung
            erfolgt auf Grundlage unserer berechtigten Interessen als auch jener der Nutzer an einem Schutz vor
            Missbrauch und sonstiger unbefugter Nutzung. Eine Weitergabe dieser Daten an Dritte erfolgt grundsätzlich
            nicht, es sei denn, sie ist zur Verfolgung unserer Ansprüche erforderlich oder es besteht eine gesetzliche
            Verpflichtung hierzu.</p>
        <ul class="m-elements">
            <li><strong>Verarbeitete Datenarten:</strong> Bestandsdaten (z.B. Namen, Adressen), Kontaktdaten (z.B.
                E-Mail, Telefonnummern), Inhaltsdaten (z.B. Eingaben in Onlineformularen), Meta-/Kommunikationsdaten
                (z.B. Geräte-Informationen, IP-Adressen).
            </li>
            <li><strong>Betroffene Personen:</strong> Nutzer (z.B. Webseitenbesucher, Nutzer von Onlinediensten).</li>
            <li><strong>Zwecke der Verarbeitung:</strong> Erbringung vertragliche Leistungen und Kundenservice,
                Sicherheitsmaßnahmen, Verwaltung und Beantwortung von Anfragen.
            </li>
            <li><strong>Rechtsgrundlagen:</strong> Einwilligung (Art. 6 Abs. 1 S. 1 lit. a. DSGVO), Vertragserfüllung
                und vorvertragliche Anfragen (Art. 6 Abs. 1 S. 1 lit. b. DSGVO), Berechtigte Interessen (Art. 6 Abs. 1
                S. 1 lit. f. DSGVO).
            </li>
        </ul>
        <h2 id="m451">Single-Sign-On-Anmeldung</h2>
        <p>Als "Single-Sign-On“ oder "Single-Sign-On-Anmeldung bzw. "-Authentifizierung“ werden Verfahren bezeichnet,
            die es Nutzern erlauben, sich mit Hilfe eines Nutzerkontos bei einem Anbieter von Single-Sign-On-Verfahren
            (z.B. einem sozialen Netzwerk), auch bei unserem Onlineangebot, anzumelden. Voraussetzung der
            Single-Sign-On-Authentifizierung ist, dass die Nutzer bei dem jeweiligen Single-Sign-On-Anbieter registriert
            sind und die erforderlichen Zugangsdaten in dem dafür vorgesehenen Onlineformular eingeben, bzw. schon bei
            dem Single-Sign-On-Anbieter angemeldet sind und die Single-Sign-On-Anmeldung via Schaltfläche
            bestätigen.</p>
        <p>Die Authentifizierung erfolgt direkt bei dem jeweiligen Single-Sign-On-Anbieter. Im Rahmen einer solchen
            Authentifizierung erhalten wir eine Nutzer-ID mit der Information, dass der Nutzer unter dieser Nutzer-ID
            beim jeweiligen Single-Sign-On-Anbieter eingeloggt ist und eine für uns für andere Zwecke nicht weiter
            nutzbare ID (sog "User Handle“). Ob uns zusätzliche Daten übermittelt werden, hängt allein von dem genutzten
            Single-Sign-On-Verfahren ab, von den gewählten Datenfreigaben im Rahmen der Authentifizierung und zudem
            davon, welche Daten Nutzer in den Privatsphäre- oder sonstigen Einstellungen des Nutzerkontos beim
            Single-Sign-On-Anbieter freigegeben haben. Es können je nach Single-Sign-On-Anbieter und der Wahl der Nutzer
            verschiedene Daten sein, in der Regel sind es die E-Mail-Adresse und der Benutzername. Das im Rahmen des
            Single-Sign-On-Verfahrens eingegebene Passwort bei dem Single-Sign-On-Anbieter ist für uns weder einsehbar,
            noch wird es von uns gespeichert. </p>
        <p>Die Nutzer werden gebeten, zu beachten, dass deren bei uns gespeicherte Angaben automatisch mit ihrem
            Nutzerkonto beim Single-Sign-On-Anbieter abgeglichen werden können, dies jedoch nicht immer möglich ist oder
            tatsächlich erfolgt. Ändern sich z.B. die E-Mail-Adressen der Nutzer, müssen sie diese manuell in ihrem
            Nutzerkonto bei uns ändern.</p>
        <p>Die Single-Sign-On-Anmeldung können wir, sofern mit den Nutzern vereinbart, im Rahmen der oder vor der
            Vertragserfüllung einsetzen, soweit die Nutzer darum gebeten wurden, im Rahmen einer Einwilligung
            verarbeiten und setzen sie ansonsten auf Grundlage der berechtigten Interessen unsererseits und der
            Interessen der Nutzer an einem effektiven und sicheren Anmeldesystem ein.</p>
        <p>Sollten Nutzer sich einmal entscheiden, die Verknüpfung ihres Nutzerkontos beim Single-Sign-On-Anbieter nicht
            mehr für das Single-Sign-On-Verfahren nutzen zu wollen, müssen sie diese Verbindung innerhalb ihres
            Nutzerkontos beim Single-Sign-On-Anbieter aufheben. Möchten Nutzer deren Daten bei uns löschen, müssen sie
            ihre Registrierung bei uns kündigen.</p>
        <ul class="m-elements">
            <li><strong>Verarbeitete Datenarten:</strong> Bestandsdaten (z.B. Namen, Adressen), Kontaktdaten (z.B.
                E-Mail, Telefonnummern).
            </li>
            <li><strong>Betroffene Personen:</strong> Nutzer (z.B. Webseitenbesucher, Nutzer von Onlinediensten).</li>
            <li><strong>Zwecke der Verarbeitung:</strong> Erbringung vertragliche Leistungen und Kundenservice,
                Anmeldeverfahren.
            </li>
            <li><strong>Rechtsgrundlagen:</strong> Einwilligung (Art. 6 Abs. 1 S. 1 lit. a. DSGVO), Vertragserfüllung
                und vorvertragliche Anfragen (Art. 6 Abs. 1 S. 1 lit. b. DSGVO), Berechtigte Interessen (Art. 6 Abs. 1
                S. 1 lit. f. DSGVO).
            </li>
        </ul>
        <p><strong>Eingesetzte Dienste und Diensteanbieter:</strong></p>
        <ul class="m-elements">
            <li><strong>Twitter Single-Sign-On:</strong> Authentifizierungsdienst; Dienstanbieter: Twitter International
                Company, One Cumberland Place, Fenian Street, Dublin 2 D02 AX07, Irland, Mutterunternehmen: Twitter
                Inc., 1355 Market Street, Suite 900, San Francisco, CA 94103, USA; Website: <a
                        href="https://twitter.com" target="_blank">https://twitter.com</a>; Datenschutzerklärung: <a
                        href="https://twitter.com/de/privacy" target="_blank">https://twitter.com/de/privacy</a>;
                Widerspruchsmöglichkeit (Opt-Out): <a href="https://twitter.com/personalization" target="_blank">https://twitter.com/personalization</a>.
            </li>

            <li><strong>Spotify Single-Sign-On:</strong>
                Authentifizierungsdienst; Dienstanbieter: Spotify AB, Regeringsgatan 19, SE-111 53 Stockholm, Schweden;
                Website: <a
                        href="https://spotify.com" target="_blank">https://spotify.com</a>; Datenschutzerklärung: <a
                        href="https://www.spotify.com/de/legal/privacy-policy/" target="_blank">https://www.spotify.com/de/legal/privacy-policy/</a>.
            </li>
        </ul>
        <h2 id="m263">Webanalyse, Monitoring und Optimierung</h2>
        <p>Die Webanalyse (auch als "Reichweitenmessung" bezeichnet) dient der Auswertung der Besucherströme unseres
            Onlineangebotes und kann Verhalten, Interessen oder demographische Informationen zu den Besuchern, wie z.B.
            das Alter oder das Geschlecht, als pseudonyme Werte umfassen. Mit Hilfe der Reichweitenanalyse können wir
            z.B. erkennen, zu welcher Zeit unser Onlineangebot oder dessen Funktionen oder Inhalte am häufigsten genutzt
            werden oder zur Wiederverwendung einladen. Ebenso können wir nachvollziehen, welche Bereiche der Optimierung
            bedürfen. </p>
        <p>Neben der Webanalyse können wir auch Testverfahren einsetzen, um z.B. unterschiedliche Versionen unseres
            Onlineangebotes oder seiner Bestandteile zu testen und optimieren.</p>
        <p>Zu diesen Zwecken können sogenannte Nutzerprofile angelegt und in einer Datei (sogenannte "Cookie")
            gespeichert oder ähnliche Verfahren mit dem gleichen Zweck genutzt werden. Zu diesen Angaben können z.B.
            betrachtete Inhalte, besuchte Webseiten und dort genutzte Elemente und technische Angaben, wie der
            verwendete Browser, das verwendete Computersystem sowie Angaben zu Nutzungszeiten gehören. Sofern Nutzer in
            die Erhebung ihrer Standortdaten eingewilligt haben, können je nach Anbieter auch diese verarbeitet
            werden.</p>
        <p>Es werden ebenfalls die IP-Adressen der Nutzer gespeichert. Jedoch nutzen wir ein IP-Masking-Verfahren (d.h.,
            Pseudonymisierung durch Kürzung der IP-Adresse) zum Schutz der Nutzer. Generell werden die im Rahmen von
            Webanalyse, A/B-Testings und Optimierung keine Klardaten der Nutzer (wie z.B. E-Mail-Adressen oder Namen)
            gespeichert, sondern Pseudonyme. D.h., wir als auch die Anbieter der eingesetzten Software kennen nicht die
            tatsächliche Identität der Nutzer, sondern nur den für Zwecke der jeweiligen Verfahren in deren Profilen
            gespeicherten Angaben.</p>
        <p><strong>Hinweise zu Rechtsgrundlagen:</strong> Sofern wir die Nutzer um deren Einwilligung in den Einsatz der
            Drittanbieter bitten, ist die Rechtsgrundlage der Verarbeitung von Daten die Einwilligung. Ansonsten werden
            die Daten der Nutzer auf Grundlage unserer berechtigten Interessen (d.h. Interesse an effizienten,
            wirtschaftlichen und empfängerfreundlichen Leistungen) verarbeitet. In diesem Zusammenhang möchten wir Sie
            auch auf die Informationen zur Verwendung von Cookies in dieser Datenschutzerklärung hinweisen.</p>
        <ul class="m-elements">
            <li><strong>Verarbeitete Datenarten:</strong> Nutzungsdaten (z.B. besuchte Webseiten, Interesse an Inhalten,
                Zugriffszeiten), Meta-/Kommunikationsdaten (z.B. Geräte-Informationen, IP-Adressen).
            </li>
            <li><strong>Betroffene Personen:</strong> Nutzer (z.B. Webseitenbesucher, Nutzer von Onlinediensten).</li>
            <li><strong>Zwecke der Verarbeitung:</strong> Reichweitenmessung (z.B. Zugriffsstatistiken, Erkennung
                wiederkehrender Besucher), Tracking (z.B. interessens-/verhaltensbezogenes Profiling, Nutzung von
                Cookies), Konversionsmessung (Messung der Effektivität von Marketingmaßnahmen), Profiling (Erstellen von
                Nutzerprofilen).
            </li>
            <li><strong>Sicherheitsmaßnahmen:</strong> IP-Masking (Pseudonymisierung der IP-Adresse).</li>
            <li><strong>Rechtsgrundlagen:</strong> Einwilligung (Art. 6 Abs. 1 S. 1 lit. a. DSGVO), Berechtigte
                Interessen (Art. 6 Abs. 1 S. 1 lit. f. DSGVO).
            </li>
        </ul>
        <p><strong>Eingesetzte Dienste und Diensteanbieter:</strong></p>
        <ul class="m-elements">
            <li><strong>Matomo:</strong> Die durch das Cookie erzeugte Informationen über Ihre Benutzung dieser Webseite
                werden nur auf unserem Server gespeichert und nicht an Dritte weitergegeben; Dienstanbieter: Webanalyse/
                Reichweitenmessung im Selbsthosting; Website: <a href="https://matomo.org/" target="_blank">https://matomo.org/</a>;
                Löschung von Daten: Die Cookies haben eine Speicherdauer von maximal 13 Monaten.
            </li>
        </ul>
        <h2 id="m12">Löschung von Daten</h2>
        <p>Die von uns verarbeiteten Daten werden nach Maßgabe der gesetzlichen Vorgaben gelöscht, sobald deren zur
            Verarbeitung erlaubten Einwilligungen widerrufen werden oder sonstige Erlaubnisse entfallen (z.B., wenn der
            Zweck der Verarbeitung dieser Daten entfallen ist oder sie für den Zweck nicht erforderlich sind).</p>
        <p>Sofern die Daten nicht gelöscht werden, weil sie für andere und gesetzlich zulässige Zwecke erforderlich
            sind, wird deren Verarbeitung auf diese Zwecke beschränkt. D.h., die Daten werden gesperrt und nicht für
            andere Zwecke verarbeitet. Das gilt z.B. für Daten, die aus handels- oder steuerrechtlichen Gründen
            aufbewahrt werden müssen oder deren Speicherung zur Geltendmachung, Ausübung oder Verteidigung von
            Rechtsansprüchen oder zum Schutz der Rechte einer anderen natürlichen oder juristischen Person erforderlich
            ist.</p>
        <p>Weitere Hinweise zu der Löschung von personenbezogenen Daten können ferner im Rahmen der einzelnen
            Datenschutzhinweise dieser Datenschutzerklärung erfolgen.</p>
        <h2 id="m15">Änderung und Aktualisierung der Datenschutzerklärung</h2>
        <p>Wir bitten Sie, sich regelmäßig über den Inhalt unserer Datenschutzerklärung zu informieren. Wir passen die
            Datenschutzerklärung an, sobald die Änderungen der von uns durchgeführten Datenverarbeitungen dies
            erforderlich machen. Wir informieren Sie, sobald durch die Änderungen eine Mitwirkungshandlung Ihrerseits
            (z.B. Einwilligung) oder eine sonstige individuelle Benachrichtigung erforderlich wird.</p>
        <p>Sofern wir in dieser Datenschutzerklärung Adressen und Kontaktinformationen von Unternehmen und
            Organisationen angeben, bitten wir zu beachten, dass die Adressen sich über die Zeit ändern können und
            bitten die Angaben vor Kontaktaufnahme zu prüfen.</p>
        <h2 id="m10">Rechte der betroffenen Personen</h2>
        <p>Ihnen stehen als Betroffene nach der DSGVO verschiedene Rechte zu, die sich insbesondere aus Art. 15 bis 21
            DSGVO ergeben:</p>
        <ul>
            <li><strong>Widerspruchsrecht: Sie haben das Recht, aus Gründen, die sich aus Ihrer besonderen Situation
                    ergeben, jederzeit gegen die Verarbeitung der Sie betreffenden personenbezogenen Daten, die aufgrund
                    von Art. 6 Abs. 1 lit. e oder f DSGVO erfolgt, Widerspruch einzulegen; dies gilt auch für ein auf
                    diese Bestimmungen gestütztes Profiling. Werden die Sie betreffenden personenbezogenen Daten
                    verarbeitet, um Direktwerbung zu betreiben, haben Sie das Recht, jederzeit Widerspruch gegen die
                    Verarbeitung der Sie betreffenden personenbezogenen Daten zum Zwecke derartiger Werbung einzulegen;
                    dies gilt auch für das Profiling, soweit es mit solcher Direktwerbung in Verbindung steht.</strong>
            </li>
            <li><strong>Widerrufsrecht bei Einwilligungen:</strong> Sie haben das Recht, erteilte Einwilligungen
                jederzeit zu widerrufen.
            </li>
            <li><strong>Auskunftsrecht:</strong> Sie haben das Recht, eine Bestätigung darüber zu verlangen, ob
                betreffende Daten verarbeitet werden und auf Auskunft über diese Daten sowie auf weitere Informationen
                und Kopie der Daten entsprechend den gesetzlichen Vorgaben.
            </li>
            <li><strong>Recht auf Berichtigung:</strong> Sie haben entsprechend den gesetzlichen Vorgaben das Recht, die
                Vervollständigung der Sie betreffenden Daten oder die Berichtigung der Sie betreffenden unrichtigen
                Daten zu verlangen.
            </li>
            <li><strong>Recht auf Löschung und Einschränkung der Verarbeitung:</strong> Sie haben nach Maßgabe der
                gesetzlichen Vorgaben das Recht, zu verlangen, dass Sie betreffende Daten unverzüglich gelöscht werden,
                bzw. alternativ nach Maßgabe der gesetzlichen Vorgaben eine Einschränkung der Verarbeitung der Daten zu
                verlangen.
            </li>
            <li><strong>Recht auf Datenübertragbarkeit:</strong> Sie haben das Recht, Sie betreffende Daten, die Sie uns
                bereitgestellt haben, nach Maßgabe der gesetzlichen Vorgaben in einem strukturierten, gängigen und
                maschinenlesbaren Format zu erhalten oder deren Übermittlung an einen anderen Verantwortlichen zu
                fordern.
            </li>
            <li><strong>Beschwerde bei Aufsichtsbehörde:</strong> Sie haben ferner nach Maßgabe der gesetzlichen
                Vorgaben das Recht, bei einer Aufsichtsbehörde, insbesondere in dem Mitgliedstaat Ihres gewöhnlichen
                Aufenthaltsorts, Ihres Arbeitsplatzes oder des Orts des mutmaßlichen Verstoßes Beschwerde einzulegen,
                wenn Sie der Ansicht sind, dass die Verarbeitung der Sie betreffenden personenbezogenen Daten gegen die
                DSGVO verstößt.
            </li>
        </ul>
        <p class="seal"><a href="https://datenschutz-generator.de/?l=de"
                           target="_blank" rel="noopener noreferrer nofollow">Textquellen:
                Datenschutz-Generator.de</a></p>
    </main>
@endsection
