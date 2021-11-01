@extends('layout.app')

@section('title') Freunde @endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="alert alert-primary">
                <b>Die Funktionen auf dieser Seite befinden sich derzeit erst in Entwicklung. Der aktuelle Stand der
                    Funktionen wird sich in der Zukunft ändern.</b>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Freundesliste</h2>
                    @if(auth()->user()->friends->count() == 0)
                        <span class="fs-bold text-danger">Du hast keine Freunde.</span>
                    @else
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Benutzername</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(auth()->user()->friends as $friend)
                                    <tr>
                                        <td>{{$friend->username}}</td>
                                        <td>
                                            <form method="POST" action="{{route('friendships.action.cancel')}}">
                                                @csrf
                                                <input type="hidden" name="friend_id" value="{{$friend->id}}"/>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2>Freund hinzufügen</h2>
                    <form method="POST" action="{{route('friendships.action.request')}}">
                        @csrf
                        <div class="mb-2">
                            <label for="request-username">Benutzername</label>
                            <input type="text" name="username" class="form-control" id="request-username"/>
                        </div>

                        <button type="submit" class="btn btn-primary">Freundschaft anfragen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
