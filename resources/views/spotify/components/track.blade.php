<div class="row">
    <div class="col-sm-4">
        @isset($track->album->imageUrl)
            <a href="/spotify/track/{{$track->id}}">
                <img src="{{$track->album->imageUrl}}" class="spotify-cover"/>
            </a>
        @endisset
    </div>
    <div class="col-sm-8">
        <a href="/spotify/track/{{$track->id}}">
            <b>
                @if($showIteration ?? false)
                    {{$loop->iteration}}.
                @endif
                {{$track->name}}
            </b>
        </a>
        <br>
        @isset($track->artists)
            <small>{{__('general.from')}}
                @foreach($track->artists as $artist)
                    <a href="{{route('spotify.artist', ['id' => $artist->id])}}">{{$artist->name}}</a>
                    @if(!$loop->last)
                        {{__('general.and')}}
                    @endif
                @endforeach
            </small>
            <br/>
        @endisset
        @isset($minutes)
            <small>{{$minutes}} {{__('spotify.minutes.heared')}}</small>
        @endisset

        @isset($track->preview_url)
            <audio controls="">
                <source src="{{$track->preview_url}}" type="audio/mpeg">
                {{__('no-browser-support')}}
            </audio>
        @endif
    </div>
</div>
@if(!isset($showAttributes) || $showAttributes)
    @include('spotify.track-attributes', ['track' => $track])
@endif
<hr/>