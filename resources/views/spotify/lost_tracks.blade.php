@extends('layout.app')

@section('title'){{ __('spotify.title.lost_tracks') }} - {{ __('spotify.find_tracks_again') }} @endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('spotify.create_playlist') }}</h5>
                    <p>{{ __('spotify.lost_tracks.description') }}</p>
                    <form method="POST" action="{{route('spotify.saveLostTracks')}}">
                        @csrf
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="spotify_createOldPlaylist"
                                   id="playlistcreate" {{$settings_active ? 'checked=""' : ''}})>
                            <label class="form-check-label"
                                   for="playlistcreate">{{ __('spotify.create_playlist') }}</label>
                        </div>
                        <div class="form-group">
                            <label>{{__('spotify.lost_tracks.minutes_until_liked')}}</label>
                            <input type="number" name="spotify_oldPlaylist_minutesTop" value="{{$settings_minutes}}"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{__('spotify.lost_tracks.daycount_lost_tracks')}}</label>
                            <input type="number" name="spotify_oldPlaylist_days" value="{{$settings_days}}"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>{{__('spotify.lost_tracks.max_tracks', ['max' => 99])}}</label>
                            <input type="number" name="spotify_oldPlaylist_songlimit" value="{{$settings_limit}}"
                                   class="form-control">
                        </div>
                        <button type="submit" name="saveSettings"
                                class="btn btn-primary">{{__('general.save')}}</button>

                        @if($settings_active && $playlist_id != NULL)
                            <a href="https://open.spotify.com/playlist/{{$playlist_id}}" target="_blank"
                               style="float: right;">{{ __('spotify.show_playlist') }}</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('spotify.lost_tracks.your_lost_tracks') }}</h5>
                    @if(count($lostTracks) == 0)
                        <p class="text-danger">{{ __('spotify.lost_tracks.no_tracks') }}</p>
                    @else
                        <table class="ui table unstackable">
                            <tbody>
                                @foreach($lostTracks as $track)
                                    <tr>
                                        <td>
                                            @isset($track->album->imageUrl)
                                                <a href="{{route('spotify.track', $track->id)}}">
                                                    <img src="{{$track->album->imageUrl}}" class="spotify-cover"
                                                         style="max-width: 110px;"/>
                                                </a>
                                            @endisset
                                        </td>
                                        <td>
                                            <a href="{{route('spotify.track', $track->id)}}">{{$track->name}}</a><br/>
                                            <small>
                                                @foreach($track->artists as $artist)
                                                    @if($loop->first){{__('general.from')}} @endif
                                                    {{$artist->name}}
                                                    @if(!$loop->last) {{__('general.and')}} @endif
                                                @endforeach
                                            </small>
                                            @isset($track->preview_url)
                                                <hr/>
                                                <audio controls>
                                                    <source src="{{$track->preview_url}}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            @endisset
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection