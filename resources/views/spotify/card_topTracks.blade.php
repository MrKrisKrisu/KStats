@foreach($topTracks as $playActivity)
    <div class="row">
        <div class="col-md-4">
            @isset($playActivity->track->album->imageUrl)
                <a href="/spotify/track/{{$playActivity->track->id}}">
                    <img src="{{$playActivity->track->album->imageUrl}}" class="spotify-cover"/>
                </a>
            @endisset
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