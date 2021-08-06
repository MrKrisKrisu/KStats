@extends('layout.app')

@section('title', 'Grocy')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Deine Instanz</h5>

                    @isset(auth()->user()->socialProfile->grocy_host)
                        <p class="text-success">
                            <i class="fas fa-check"></i> Die Zugangsdaten zu deiner Grocy-Instanz sind gespeichert.
                        </p>

                        @isset($systemInfo?->grocy_version?->Version)

                            <p>
                                Die Verbindung zu <b>{{auth()->user()->socialProfile->grocy_host}}</b> ist fehlerfrei.<br/>
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
@endsection

