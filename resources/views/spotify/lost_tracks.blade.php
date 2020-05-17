@extends('layout.app')

@section('title')Verschollene Tracks - Entdecke alte Lieder wieder! @endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Playlist erstellen</h5>
                    <p>Wir erstellen dir eine Playlist mit Songs, die du länger, aber früher öfters gehört hast.</p>
                    <form method="POST" action="{{route('spotify.saveLostTracks')}}">
                        @csrf
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="spotify_createOldPlaylist"
                                   id="playlistcreate" {{$settings_active ? 'checked=""' : ''}})>
                            <label class="form-check-label" for="playlistcreate">Playlist erstellen</label>
                        </div>
                        <div class="form-group">
                            <label>Gehörte Minuten, ab dem ein Lied als "Mag ich" eingestuft wird</label>
                            <input type="number" name="spotify_oldPlaylist_minutesTop" value="{{$settings_minutes}}"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Anzahl an Tagen, bis ein Lied als "alt" gilt</label>
                            <input type="number" name="spotify_oldPlaylist_days" value="{{$settings_days}}"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Maximale Lieder in der Playlist (max. 99)</label>
                            <input type="number" name="spotify_oldPlaylist_songlimit" value="{{$settings_limit}}"
                                   class="form-control">
                        </div>
                        <button type="submit" name="saveSettings" class="btn btn-primary">Speichern</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tracks</h5>
                    @if(count($lostTracks) == 0)
                        <p style="font-weight: bold; color: #E70000;">Du hast aktuelle keine verschollenen Tracks. Passe den Filter an oder schaue in einigen Tagen
                            nochmal vorbei.</p>
                    @else
                        <table class="ui table unstackable">
                            <thead>
                            <tr>
                                <th>Song</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lostTracks as $track)
                                <tr>
                                    <td>{{$track->name}}</td>
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