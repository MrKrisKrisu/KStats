@extends('layout.app')

@section('title') {{__('spotify.title.explore')}} @endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Wie findest du diesen Track?</h2>
                    <div class="row">
                        <div class="col-md-4">
                            @isset($track->album->imageUrl)
                                <a href="/spotify/track/{{$track->id}}">
                                    <img src="{{$track->album->imageUrl}}" class="spotify-cover"/>
                                </a>
                            @endisset
                        </div>
                        <div class="col">
                            <a href="/spotify/track/{{$track->id}}">
                                <b>{{$track->name}}</b>
                            </a>
                            <br>
                            @isset($track->artists)
                                <small>von
                                    @foreach($track->artists as $artist)
                                        <a href="{{route('spotify.artist', ['id' => $artist->id])}}">{{$artist->name}}</a>
                                        @if(!$loop->last) und @endif
                                    @endforeach
                                </small>
                                <br/>
                            @endisset

                            @isset($track->preview_url)
                                <audio controls="" @if(Session::has('autoplay')) autoplay @endif>
                                    <source src="{{$track->preview_url}}" type="audio/mpeg">
                                    Your browser does not support the audio element.';
                                </audio>
                            @endif

                            <div class="button-group float-right">
                                <form method="POST" action="{{route('spotify.explore.submit')}}">
                                    @csrf

                                    <input type="hidden" name="track_id" value="{{$track->id}}"/>

                                    <button type="button" class="btn btn-success" data-toggle="tooltip"
                                            data-placement="top" title="Gefällt mir" id="btnLike"
                                            onclick="$('#likedModal').modal('show'); $('#btnPlaylist').focus();">
                                        <i class="fas fa-thumbs-up"></i>
                                    </button>
                                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" id="btnDislike"
                                            data-placement="top" title="Gefällt mir nicht" name="rating" value="0">
                                        <i class="fas fa-thumbs-down"></i>
                                    </button>
                                    <button type="submit" class="btn btn-secondary" data-toggle="tooltip" id="btnSkip"
                                            data-placement="top" title="Überspringen" name="rating" value="-1">
                                        <i class="fas fa-forward"></i>
                                    </button>
                                </form>
                                <script>
                                    document.onkeyup = function (e) {
                                        if (e.which == 187 || e.which == 107) {
                                            $('#btnLike').click();
                                        } else if (e.which == 189 || e.which == 109) {
                                            $('#btnDislike').click();
                                        } else if (e.which == 78) {
                                            $('#btnSkip').click();
                                        }
                                    };
                                </script>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <hr/>
                            <small>
                                <b>Warum wird mir dieser Track angezeigt?</b><br/>
                                {{$trackReason}}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Wie funktioniert das?</h2>
                    <p class="text-muted">Auf der <b>Entdecken</b> Seite werden dir Tracks gezeigt, welche du bisher
                        noch nicht gehört hast, welche aber beliebt sind. Wenn er dir gefällt kannst du ihn hier direkt
                        in deine Spotify Bibliothek hinzufügen!</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <span class="color-primary" style="font-size: 30px;">{{$exploredToday}}</span><br/>
                            <span>entdeckte Tracks heute</span>
                        </div>
                        <div class="col">
                            <span class="color-primary" style="font-size: 30px;">{{$exploredTotal}}</span><br/>
                            <span>entdeckte Tracks gesamt</span>
                        </div>
                        <div class="col">
                            <span class="color-primary" style="font-size: 30px;">{{$ratedTotal}}</span><br/>
                            <span>bewertete Tracks gesamt</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h2>Entdecken in Telegram</h2>
                    <p class="text-muted">
                        Du kannst einmal am Tag ein Lied per Telegram zugeschickt bekommen, welches du dann ganz einfach
                        per Knopfdruck bewerten kannst. <br />Wähle hier die Uhrzeit dazu:
                    </p>
                    <form method="POST" action="{{route('spotify.explore.telegram')}}">
                        @csrf
                        <div class="form-group">
                            <input type="time" name="time" class="form-control"
                                   value="{{\App\Models\UserSettings::get(auth()->user()->id, 'tg_explore_time')}}"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Speichern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="likedModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="POST" action="{{route('spotify.explore.submit')}}">
                @csrf

                <input type="hidden" name="track_id" value="{{$track->id}}"/>
                <input type="hidden" name="rating" value="1"/>

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Schön, dass es dir gefällt!</h5>
                    </div>
                    <div class="modal-body">
                        <p>Möchtest du diesen Track in deine Spotify Bibliothek hinzufügen?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" name="addToPlaylist" value="1" id="btnPlaylist">Ja, hinzufügen
                        </button>
                        <button class="btn btn-secondary" name="addToPlaylist" value="0">Nein</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection