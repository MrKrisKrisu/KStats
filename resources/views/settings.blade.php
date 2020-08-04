@extends('layout.app')

@section('title')Einstellungen @endsection

@section('content')
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ _('Spotify Connect') }}</h5>
                    @if($isConnectedToSpotify)
                        {{ _('You are already connected to Spotify.') }}
                        {{ _('Click the button to reconnect.') }}
                    @else
                        {{ _('You are not connected to Spotify.') }}
                        {{ _('Click the button to connect.') }}
                    @endif
                    <hr/>
                    <a href="{{route('redirectProvider', 'spotify')}}" class="btn btn-success">Spotify Connect</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ _('Twitter Connect') }}</h5>

                    <p>Status:
                        @if($isConnectedToTwitter)
                            <span style="color: green; font-weight: bold;">Connected</span>
                        @else
                            <span style="color: red; font-weight: bold;">Not Connected</span>
                        @endif

                    </p>
                    <hr/>
                    <a href="{{route('redirectProvider', 'twitter')}}" class="btn btn-success">
                        @if($isConnectedToTwitter)
                            Reconnect to Twitter
                        @else
                            Connect to Twitter
                        @endif
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ _('Telegram Connect') }}</h5>
                    @if($isConnectedToTelegram)
                        <p>Der Account ist bereits mit einem Telegram Konto verknüpft.</p>

                        <form method="POST" action="{{route('settings.connections.telegram.delete')}}"
                              class="float-right">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Deaktivieren</button>
                        </form>

                    @else
                        <p>Du bist aktuell mit <b>keinem</b> Telegram Chat verbunden.</p>
                    @endif
                    @if($telegramConnectCode != NULL && $telegramConnectCode->val != '')

                        <div style="text-align: center;">
                            <p style="font-size: 20px;">Dein Telegram-ConnectCode lautet
                                "<b>{{$telegramConnectCode->val}}</b>"
                                <br/><small>Code gültig
                                    bis {{$telegramConnectCode->updated_at->addHour()->isoFormat('Do MMMM YYYY, HH:mm')}}</small>
                            </p>
                        </div>
                        <p>Um Telegram mit KStats nutzen zu können musst du den
                            <a target="tg" href="https://t.me/kstat_bot">KStats Bot</a> starten und den Anweisungen
                            folgen.
                        </p>
                        <small>Wenn du die Anweisungen nicht erhältst schicke dem Bot bitte "/start".</small>
                    @endif

                    <form method="POST" action="{{ route('settings') }}">
                        @csrf
                        <input type="hidden" name="action" value="createTelegramToken"/>
                        <button type="submit" class="btn btn-primary">@if($isConnectedToTelegram) Neuen @endif Account
                            verknüpfen
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- TODO: Nur sporadisch mal gebastelt -->
                    <h5 class="card-title">Zugeordnete E-Mail Adressen</h5>
                    @if(empty($emails))
                        <p><b>Es sind aktuell keine E-Mail Adressen hinterlegt.</b></p>
                    @else
                        <p>Folgende E-Mail Adressen sind mit deinem KStats Account verbunden:</p>
                        <ul>
                            @foreach($emails as $email)
                                <li>{{$email->email}} <small>@if($email->verified_user_id !== NULL) <span
                                                style="color: green;">verifiziert</span> @else <span
                                                style="color: #E70000;">unverifiziert</span> @endif</small></li>
                            @endforeach
                        </ul>
                    @endif
                    <form method="POST" action="/settings" class="form-inline">
                        @csrf
                        <input type="hidden" name="action" value="addEMail"/>
                        <input type="email" name="email" placeholder="E-Mail Adresse" class="form-control"/>
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </form>
                    <hr/>
                    <small><b>Warum ist das wichtig?</b> Für den REWE eBon Analyzer müssen alle E-Mail Adressen
                        hinzugefügt werden, an die Kassenzettel geschickt werden.</small>
                </div>
            </div>
        </div>
    </div>
@endsection

