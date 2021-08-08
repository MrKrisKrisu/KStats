@if($track->popularity > 80)
    <label class="badge badge-sm badge-success" data-toggle="tooltip" data-placement="top"
           title="{{__('spotify.trending.text')}}">
        <i class="fas fa-chart-line"></i> {{__('spotify.trending')}}
    </label>
@elseif($track->popularity > 60)
    <label class="badge badge-sm badge-primary" data-toggle="tooltip" data-placement="top"
           title="{{__('spotify.popular.text')}}">
        <i class="far fa-thumbs-up"></i> {{__('spotify.popular')}}
    </label>
@elseif($track->popularity < 15)
    <label class="badge badge-sm badge-danger" data-toggle="tooltip" data-placement="top"
           title="{{__('spotify.lost.text')}}">
        <i class="fas fa-question"></i> {{__('spotify.lost')}}
    </label>
@endif

@if($track->valence > 0.4)
    <label class="badge badge-sm badge-success" data-toggle="tooltip" data-placement="top"
           title="{{__('spotify.happy.text')}}">
        <i class="far fa-smile"></i> {{__('spotify.happy')}}
    </label>
@elseif($track->valence < 0.3)
    <label class="badge badge-sm badge-danger" data-toggle="tooltip" data-placement="top"
           title="{{__('spotify.sad.text')}}">
        <i class="far fa-sad-tear"></i> {{__('spotify.sad')}}
    </label>
@endif

@if($track->danceability > 0.5)
    <label class="badge badge-sm badge-primary" data-toggle="tooltip" data-placement="top"
           title="{{__('spotify.dance.text')}}">
        <i class="fas fa-walking"></i> {{__('spotify.dance')}}
    </label>
@endif

@if($track->speechiness > 0.6)
    <label class="badge badge-sm badge-primary" data-toggle="tooltip" data-placement="top"
           title="{{__('spotify.speech')}}">
        <i class="far fa-comments"></i>
    </label>
@elseif($track->speechiness > 0.4)
    <label class="badge badge-sm badge-primary" data-toggle="tooltip" data-placement="top"
           title="{{__('spotify.speech')}}">
        <i class="far fa-comment"></i>
    </label>
@endif