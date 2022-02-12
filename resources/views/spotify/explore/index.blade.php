@extends('layout.app')

@section('title', __('spotify.title.explore'))

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-body">
                    <h2>{{__('track.opinion')}}</h2>
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
                                        @if(!$loop->last) {{__('general.and')}} @endif
                                    @endforeach
                                </small>
                                <br/>
                            @endisset

                            @isset($track->preview_url)
                                <audio controls="" @if(session()->has('autoplay')) autoplay @endif>
                                    <source src="{{$track->preview_url}}" type="audio/mpeg">
                                    {{__('no-browser-support')}}
                                </audio>
                            @endif

                            <div class="button-group float-end">
                                <form method="POST" action="{{route('spotify.explore.submit')}}">
                                    @csrf

                                    <input type="hidden" name="track_id" value="{{$track->id}}"/>

                                    <button type="button" class="btn btn-success" data-bs-toggle="tooltip"
                                            data-placement="top" title="{{__('like')}}" id="btnLike"
                                            onclick="$('#likedModal').modal('show'); $('#btnPlaylist').focus();">
                                        <i class="fas fa-thumbs-up"></i>
                                    </button>
                                    <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip"
                                            id="btnDislike"
                                            data-placement="top" title="{{__('dislike')}}" name="rating" value="0">
                                        <i class="fas fa-thumbs-down"></i>
                                    </button>
                                    <button type="submit" class="btn btn-secondary" data-bs-toggle="tooltip"
                                            id="btnSkip"
                                            data-placement="top" title="{{__('skip')}}" name="rating" value="-1">
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
                                <b>{{__('explore.why')}}</b><br/>
                                {{$trackReason}}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-body">
                    <h2>{{__('how-does-it-work')}}</h2>
                    <p class="text-muted">{{__('explore.explain')}}</p>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <span class="color-primary" style="font-size: 30px;">{{$exploredToday}}</span><br/>
                            <span>{{__('explored.today')}}</span>
                        </div>
                        <div class="col">
                            <span class="color-primary" style="font-size: 30px;">{{$exploredTotal}}</span><br/>
                            <span>{{__('explored.total')}}</span>
                        </div>
                        <div class="col">
                            <span class="color-primary" style="font-size: 30px;">{{$ratedTotal}}</span><br/>
                            <span>{{__('rated.total')}}</span>
                        </div>
                    </div>
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
                        <h5 class="modal-title">{{__('explore.glad-to')}}</h5>
                    </div>
                    <div class="modal-body">
                        <p>{{__('spotify.add-to-bib')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" name="addToPlaylist" value="1" id="btnPlaylist">
                            {{__('yes-add')}}
                        </button>
                        <button class="btn btn-secondary" name="addToPlaylist" value="0">{{__('general.no')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection