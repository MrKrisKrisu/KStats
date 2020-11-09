@foreach($topTracks as $playActivity)
    <div class="row">
        <div class="col-md-4">
            @isset($playActivity->track->album->imageUrl)
                <a href="/spotify/track/{{$playActivity->track->id}}">
                    <img src="{{$playActivity->track->album->imageUrl}}" class="spotify-cover"/>
                </a>
            @endisset
            <div class="btn-group emoticons">
                @if($playActivity->track->valence > 0.4)
                    <button class="btn btn-sm btn-outline-success" data-toggle="tooltip" data-placement="top"
                            title="Dieses Lied ist macht gute Laune.">
                        <i class="far fa-smile fa-2x"></i>
                    </button>
                @elseif($playActivity->track->valence > 0.2)
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="far fa-meh fa-2x"></i>
                    </button>
                @elseif($playActivity->track->valence != null)
                    <button class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top"
                            title="Dieses Lied hat eher eine traurige bzw. aggressive Stimmung.">
                        <i class="far fa-sad-tear fa-2x"></i>
                    </button>
                @endif

                @if($playActivity->track->danceability > 0.6)
                    <button class="btn btn-sm btn-outline-success" data-toggle="tooltip" data-placement="top"
                            title="Zu diesem Lied kann man gut tanzen.">
                        <i class="fas fa-walking fa-2x"></i>
                    </button>
                @endif

                @if($playActivity->track->speechiness > 0.6)
                    <button class="btn btn-sm btn-outline-info" data-toggle="tooltip" data-placement="top"
                            title="In diesem Track wird viel gesprochen.">
                        <i class="far fa-comments fa-2x"></i>
                    </button>
                @elseif($playActivity->track->speechiness > 0.4)
                    <button class="btn btn-sm btn-outline-dark" data-toggle="tooltip" data-placement="top"
                            title="In diesem Track wird viel gesprochen.">
                        <i class="far fa-comment fa-2x"></i>
                    </button>
                @endif
            </div>
        </div>
        <div class="col">
            <a href="/spotify/track/{{$playActivity->track->id}}">
                <b>{{$playActivity->track->name}}</b>
            </a>
            <br>
            @isset($playActivity->track->artists)
                <small>von
                    @foreach($playActivity->track->artists as $artist)
                        {{$artist->name}}
                        @if(!$loop->last) und @endif
                    @endforeach
                </small>
                <br/>
            @endisset
            <small>{{$playActivity->minutes}} Minuten gehört</small>

            @isset($playActivity->track->preview_url)
                <audio controls="">
                    <source src="{{$playActivity->track->preview_url}}" type="audio/mpeg">
                    Your browser does not support the audio element.';
                </audio>
            @endif
        </div>
    </div>
    <hr/>
@endforeach

{{$topTracksTotal->fragment($fragment)->onEachSide(1)->links()}}