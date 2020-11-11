@foreach($topTracks as $playActivity)
    @include('spotify.components.track', ['track' => $playActivity->track, 'minutes' => $playActivity->minutes])
@endforeach

{{$topTracksTotal->fragment($fragment)->onEachSide(1)->links()}}