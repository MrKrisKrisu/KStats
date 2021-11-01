@extends('layout.app')

@section('title', 'grocy - ERP beyond your fridge')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-server"></i> Deine Instanz</h5>

                    @isset(auth()->user()->socialProfile->grocy_host)
                        <p class="text-success">
                            <i class="fas fa-check"></i> Die Zugangsdaten zu deiner Grocy-Instanz sind gespeichert.
                        </p>

                        @isset($systemInfo?->grocy_version?->Version)

                            <p>
                                Die Verbindung zu <b>{{auth()->user()->socialProfile->grocy_host}}</b> ist
                                fehlerfrei.<br/>
                                Software-Version: v{{$systemInfo?->grocy_version?->Version}}
                            </p>
                        @else
                            <p class="text-warning">
                                <i class="fas fa-exclamation-triangle"></i> Es konnte keine Daten von deiner
                                Installation ermittelt werden. Bitte prüfe die Verbindung.
                            </p>
                        @endisset

                        <hr/>
                        <form method="POST" action="{{route('grocy.disconnect')}}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">
                                Verbindung trennen
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{route('grocy.connect')}}">
                            @csrf

                            <div class="form-group">
                                <label>Hostname</label>
                                <input type="url" name="host" placeholder="beginnt mit http:// oder https://"
                                       class="form-control"/>
                            </div>

                            <div class="form-group">
                                <label>API-Key</label>
                                <input type="password" name="apiKey" class="form-control"/>
                            </div>

                            <button type="submit" class="btn btn-sm btn-success">
                                Verbindung herstellen
                            </button>
                        </form>
                    @endisset
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <img src="{{url('img/grocy_logo.svg')}}" class="float-end" style="height: 1.125rem"/>
                    <h5 class="card-title"><i class="far fa-question-circle"></i> Über die grocy Integration</h5>

                    <p>grocy ist eine webbasierte, selbst gehostete Lösung zur Verwaltung von Lebensmitteln und
                        Haushaltswaren für Zuhause. Mit der KStats Integration kann automatisch bei hier aufbereiteten
                        Kassenzetteln eine Buchung in grocy vorgenommmen werden.</p>
                    <p>Das heißt: <b>Du gehst einkaufen, dein Kassenzettel wird von KStats verarbeitet, dein
                            Lagerbestand bei grocy wird erhöht.</b></p>

                    <p>
                        Mehr Informationen zu grocy gibt es
                        <a href="https://github.com/grocy/grocy/blob/release/README.md" target="grocy">hier</a>.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-book"></i> Anleitung zum Einrichten</h5>
                    <p class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Es wird vorausgesetzt, dass du bereits eine grocy Installation besitzt.
                    </p>
                    <div class="row">
                        <div class="col-md-4">
                            <h6>1. API-Schlüssel generieren</h6>
                            <span>
                                Öffne deine grocy-Installation und klicke oben rechts auf den Schraubenschlüssel.
                                Wähle im Menü "API-Schlüssel verwalten" aus.
                                Erstelle einen neuen API-Schlüssel mit dem Button oben rechts und kopiere ihn.
                            </span>
                        </div>
                        <div class="col-md-4">
                            <h6>2. Instanz verbinden</h6>
                            <span>
                                Verbinde deine grocy-Installation mit KStats indem du auf dieser Seite oben deinen
                                API-Schlüssel einträgst. In das Feld <i>Hostname</i> schreibst du, unter welcher URL
                                deine Installation erreichbar ist. Am Anfang muss entweder <i>http://</i> oder
                                <i>https://</i> stehen. Bitte verwende am Schluss keinen Schrägstrich.
                            </span>
                        </div>
                        <div class="col-md-4">
                            <h6>3. Datenpflege</h6>
                            <span>
                                Damit deine Einkäufe korrekt zugeordnet werden können musst du deine grocy-Datenbank
                                kontinuerlich entsprechend pflegen.
                                Erfasse dazu bei den Produkten, die du automatisch inventarisieren möchtest den
                                exakten Produktnamen vom Kassenzettel als Barcode in grocy ein.
                            </span>
                            <hr/>
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Steht auf dem Kassenzettel z.B. "EIER FH RES S-XL", dann musst du das exakt so
                                eintragen. In Großbuchstaben, mit Leerzeichen und Bindestrichen.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

