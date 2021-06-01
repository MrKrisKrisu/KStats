<div class="card">
    <div class="card-body">
        <h2>Verbundene Drittanbieter</h2>

        <table class="table">
            <tbody>
                <tr>
                    <td><i class="fab fa-spotify"></i> Spotify</td>
                    <td>
                        @if(auth()->user()->socialProfile->isConnectedSpotify)
                            <span class="font-weight-bold text-success">
                                <i class="fas fa-check"></i>
                                {{__('settings.third-party.connected')}}
                            </span>
                            <br/>
                            <span class="text-secondary">
                                Spotify UserID: {{auth()->user()->socialProfile->spotify_user_id}}
                            </span>
                        @else
                            @isset(auth()->user()->socialProfile->spotify_lastRefreshed)
                                <p class="font-weight-bold text-danger">
                                    <i class="fas fa-times"></i>
                                    Verbindung verloren
                                    {{auth()->user()->socialProfile->spotify_lastRefreshed->diffForHumans()}}
                                </p>
                            @else
                                <p class="font-weight-bold text-secondary">{{__('settings.third-party.not-connected')}}</p>
                            @endisset
                            <a href="{{route('redirectProvider', 'spotify')}}" class="btn btn-sm btn-primary">
                                {{strtr(__('settings.third-party.connect-to'), [':thirdparty' => 'Spotify'])}}
                            </a>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td><i class="fab fa-twitter"></i> Twitter</td>
                    <td>
                        @if(auth()->user()->socialProfile->isConnectedTwitter)
                            <span class="font-weight-bold text-success">
                                <i class="fas fa-check"></i>
                                {{__('settings.third-party.connected')}}
                            </span>
                            <br/>
                            <span class="text-secondary">
                                Twitter UserID: {{auth()->user()->socialProfile->twitter_id}}
                            </span>
                        @else
                            <p class="font-weight-bold text-secondary">{{__('settings.third-party.not-connected')}}</p>
                            <a href="{{route('redirectProvider', 'twitter')}}" class="btn btn-sm btn-primary">
                                {{strtr(__('settings.third-party.connect-to'), [':thirdparty' => 'Twitter'])}}
                            </a>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td><i class="fab fa-telegram"></i> Telegram</td>
                    <td>
                        @if(auth()->user()->socialProfile->isConnectedTelegram)
                            <span class="font-weight-bold text-success">
                                <i class="fas fa-check"></i>
                                {{__('settings.third-party.connected')}}
                            </span>
                            <br/>
                            <span class="text-secondary">
                                ChatID: {{auth()->user()->socialProfile->telegram_id}}
                            </span>

                            <form method="POST" action="{{route('settings.connections.telegram.delete')}}"
                                  class="float-right">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">{{__('general.deactivate')}}</button>
                            </form>
                        @else
                            <p class="font-weight-bold text-secondary">{{__('settings.third-party.not-connected')}}</p>
                        @endif
                        @if(isset($telegramConnectCode) && ($telegramConnectCode != null && $telegramConnectCode->val != ''))
                            <div class="alert alert-info" style="text-align: center;">
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
                            <button type="submit" class="btn btn-sm btn-primary">
                                {{strtr(__('settings.third-party.connect-to'), [':thirdparty' => 'Telegram'])}}
                            </button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
