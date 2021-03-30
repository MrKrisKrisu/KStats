@extends('layout.app')

@section('title')Einstellungen @endsection

@section('content')
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Spotify Connect') }}</h5>
                    @if(auth()->user()->socialProfile->isConnectedSpotify)
                        {{ __('You are already connected to Spotify.') }}
                        {{ __('Click the button to reconnect.') }}
                    @else
                        {{ __('You are not connected to Spotify.') }}
                        {{ __('Click the button to connect.') }}
                    @endif
                    <hr/>
                    <a href="{{route('redirectProvider', 'spotify')}}" class="btn btn-success">Spotify Connect</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Twitter Connect') }}</h5>

                    <p>Status:
                        @if(auth()->user()->socialProfile->isConnectedTwitter)
                            <span class="text-success">Connected</span>
                        @else
                            <span class="text-danger">Not Connected</span>
                        @endif

                    </p>
                    <hr/>
                    <a href="{{route('redirectProvider', 'twitter')}}" class="btn btn-success">
                        @if(auth()->user()->socialProfile->isConnectedTwitter)
                            Reconnect to Twitter
                        @else
                            Connect to Twitter
                        @endif
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Telegram Connect') }}</h5>
                    @if(auth()->user()->socialProfile->isConnectedTelegram)
                        <p>{{__('settings.telegram.connected')}}</p>

                        <form method="POST" action="{{route('settings.connections.telegram.delete')}}"
                              class="float-right">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">{{__('general.deactivate')}}</button>
                        </form>

                    @else
                        <p>{{__('settings.telegram.not_connected')}}</p>
                    @endif
                    @if($telegramConnectCode != NULL && $telegramConnectCode->val != '')

                        <div style="text-align: center;">
                            <p style="font-size: 20px;">{{__('settings.telegram.connect_code')}}:
                                "<b>{{$telegramConnectCode->val}}</b>"
                                <br/><small>{{__('settings.telegram.valid_until')}} {{$telegramConnectCode->updated_at->addHour()->isoFormat('Do MMMM YYYY, HH:mm')}}</small>
                            </p>
                        </div>
                        <p>{!! __('settings.telegram.description') !!}</p>
                    @endif

                    <form method="POST" action="{{ route('settings') }}">
                        @csrf
                        <input type="hidden" name="action" value="createTelegramToken"/>
                        <button type="submit" class="btn btn-primary">
                            @if(auth()->user()->socialProfile->isConnectedTelegram)
                                {{__('settings.connect')}}
                            @else
                                {{__('settings.connect_new')}}
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Zugeordnete E-Mail Adressen</h5>
                    @if(count($emails) == 0)
                        <p class="text-danger">Es sind aktuell keine E-Mail Adressen hinterlegt.</p>
                    @else
                        <p>Folgende E-Mail Adressen sind mit deinem KStats Account verbunden:</p>
                        <table class="table">
                            <tbody>
                            @foreach($emails as $email)
                                <tr>
                                    <td>{{$email->email}}</td>
                                    <td>
                                        @if($email->verified_user_id !== NULL)
                                            <span style="color: green;">verifiziert</span>
                                        @else
                                            <span style="color: #E70000;">unverifiziert</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{route('settings.delete.email')}}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$email->id}}"/>
                                            <button type="submit" class="btn btn-sm btn-danger"><i
                                                        class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                    <hr/>
                    <h6>E-Mail Adresse hinzufügen</h6>
                    <form method="POST" action="{{route('settings.save.email')}}">
                        @csrf
                        <div class="form-group">
                            <input type="email" name="email" placeholder="E-Mail Adresse" class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </form>
                    <hr/>
                    <small><b>Warum ist das wichtig?</b> Für den REWE eBon Analyzer müssen alle E-Mail Adressen
                        hinzugefügt werden, an die Kassenzettel geschickt werden.</small>
                </div>
            </div>

            <div class="card">

                <div class="card-body">
                    <h5 class="card-title">{{__('settings.password.change')}}</h5>
                    <form method="POST" action="{{route('settings.user.password.change')}}">
                        @csrf
                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{__('settings.password.current')}}</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" name="current_password"
                                       autocomplete="current-password" required/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{__('settings.password.new')}}</label>

                            <div class="col-md-8">
                                <input id="new_password" type="password" class="form-control" name="new_password"
                                       autocomplete="current-password" required/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{__('settings.password.new_repeat')}}</label>

                            <div class="col-md-8">
                                <input id="new_confirm_password" type="password" class="form-control"
                                       name="new_confirm_password" autocomplete="current-password" required/>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('general.save')}}</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('settings.set_language')}}</h5>

                    <form method="POST" action="{{route('settings.set.lang')}}">
                        @csrf
                        <div class="form-group">
                            <select name="locale" class="form-control" required>
                                <option value="">{{__('settings.select')}}</option>
                                <option value="de"
                                        @if($user->locale == 'de') selected @endif>{{__('settings.lang.de')}}</option>
                                <option value="en"
                                        @if($user->locale == 'en') selected @endif>{{__('settings.lang.en')}}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('general.save')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

