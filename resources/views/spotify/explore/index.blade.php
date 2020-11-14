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
                                        if (e.which == 187) {
                                            $('#btnLike').click();
                                        } else if (e.which == 189) {
                                            $('#btnDislike').click();
                                        } else if (e.which == 9) {
                                            $('#btnSkip').click();
                                        }
                                    };
                                </script>
                            </div>
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
                        <button class="btn btn-primary" name="addToPlaylist" value="1" id="btnPlaylist">Ja, hinzufügen</button>
                        <button class="btn btn-secondary" name="addToPlaylist" value="0">Nein</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection