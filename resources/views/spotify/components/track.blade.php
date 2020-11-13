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
        @isset($minutes)
            <small>{{$minutes}} Minuten gehört</small>
        @endisset

        @isset($track->preview_url)
            <audio controls="">
                <source src="{{$track->preview_url}}" type="audio/mpeg">
                Your browser does not support the audio element.';
            </audio>
        @endif
    </div>
</div>
@if(!isset($showAttributes) || $showAttributes)
    @include('spotify.track-attributes', ['track' => $track])
@endif
<hr/>