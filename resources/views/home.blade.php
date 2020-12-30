@extends('layout.app')

@section('title') KStats - Übersicht @endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @isset($lastSpotifyTrack)
                        <small class="text-muted float-right">abgespielt {{$lastSpotifyTrack->timestamp_start->diffForHumans()}}</small>
                    @endisset
                    <h5 class="card-title">Dein zuletzt gehörter Track</h5>
                    @isset($lastSpotifyTrack)
                        @include('spotify.components.track', ['track' => $lastSpotifyTrack->track])
                    @else
                        <p class="text-danger">
                            Du hast bisher noch kein Lied auf Spotify gehört oder nutzt die KStats
                            Statistik nicht! :c
                        </p>
                    @endisset
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Verbundene Dienste</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Service</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Spotify</td>
                                <td>
                                    @if(auth()->user()->socialProfile->isConnectedSpotify)
                                        <p>
                                                <span class="font-weight-bold text-success">
                                                    <i class="fas fa-check"></i>
                                                    Statistiken werden gesammelt.
                                                </span><br/>
                                            <span class="text-secondary">
                                                    Spotify UserID: {{auth()->user()->socialProfile->spotify_user_id}}
                                                </span>
                                        </p>
                                    @else
                                        @isset(auth()->user()->socialProfile->spotify_lastRefreshed)
                                            <p class="font-weight-bold text-danger">
                                                <i class="fas fa-times"></i>
                                                Verbindung verloren
                                                {{auth()->user()->socialProfile->spotify_lastRefreshed->diffForHumans()}}
                                            </p>
                                        @else
                                            <p class="font-weight-bold text-secondary">
                                                ¯\_(ツ)_/¯ Nicht verbunden
                                            </p>
                                        @endisset
                                        <a href="{{route('redirectProvider', 'spotify')}}"
                                           class="btn btn-sm btn-success">Mit Spotify verbinden</a>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>Twitter</td>
                                <td>
                                    @if(auth()->user()->socialProfile->isConnectedTwitter)
                                        <p>
                                                <span class="font-weight-bold text-success">
                                                    <i class="fas fa-check"></i>
                                                    Statistiken werden gesammelt.
                                                </span><br/>
                                            <span class="text-secondary">
                                                    Twitter UserID: {{auth()->user()->socialProfile->twitter_id}}
                                                </span>
                                        </p>
                                    @else
                                        <p class="font-weight-bold text-secondary">
                                            ¯\_(ツ)_/¯ Nicht verbunden
                                        </p>
                                        <a href="{{route('redirectProvider', 'twitter')}}"
                                           class="btn btn-sm btn-primary">Mit Twitter verbinden</a>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
