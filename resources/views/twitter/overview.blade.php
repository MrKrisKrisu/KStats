@extends('layout.app')

@section('title')Twitter @endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="alert-message alert-message-default">
                <h4>Funktion in Testphase</h4>
                <p>Statistiken zu Twitter sind aktuell in der Planung bzw. Entwicklung. Aktuell siehst du an dieser
                    Stelle nur deine aktuellen Follower und eine Liste von Profilen, welche dir entfolgt sind. Ideen?
                    Trage gerne zu KStats auf <a href="https://github.com/MrKrisKrisu/KStats" target="ghub">GitHub</a>
                    bei!</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dein Profil</h5>
                    <table class="table">
                        <tr>
                            <td>Name</td>
                            <td>{{$twitter_profile->name}}</td>
                        </tr>
                        <tr>
                            <td>@-Name</td>
                            <td>{{'@'.$twitter_profile->screen_name}}</td>
                        </tr>
                        <tr>
                            <td>Follower</td>
                            <td>{{$twitter_profile->followers_count}}</td>
                        </tr>
                        <tr>
                            <td>Friends</td>
                            <td>{{$twitter_profile->friends_count}}</td>
                        </tr>
                        <tr>
                            <td>Anzahl Tweets</td>
                            <td>{{$twitter_profile->statuses_count}}</td>
                        </tr>
                    </table>
                </div>

            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Unfollower</h5>

                    @if(count($twitter_profile->unfollower)  > 0)
                        <table class="table" id="unfollowers">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Festgestellt</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($twitter_profile->unfollower as $unfollower)
                                @isset($unfollower->unfollower_profile)
                                    <tr>
                                        <td>{{$unfollower->unfollower_profile->screen_name ?? $unfollower->unfollower_profile->id}}</td>
                                        <td data-order="{{$unfollower->created_at}}">{{$unfollower->created_at->diffForHumans()}}</td>
                                    </tr>
                                @endisset
                            @endforeach

                            </tbody>
                        </table>
                        <script>
                            $('#unfollowers').DataTable({
                                "language": {
                                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/German.json"
                                },
                                "order": [[1, 'desc']],
                                "pageLength": 5,
                                "lengthMenu": [5, 10, 25, 50, 75, 100],
                                "searching": false
                            });
                        </script>
                    @else
                        <p>Es wurden bisher keine Unfollower festgestellt.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Deine Follower</h5>
                    <table class="table table-striped table-hover" id="followers">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Follower</th>
                            <th>Friends</th>
                            <th>Tweets</th>
                            <th>Accounterstellung</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($twitter_profile->followers as $follower)
                            <tr>
                                <td>
                                    <a href="https://twitter.com/{{$follower->screen_name}}"
                                       target="twitter">{{'@'.$follower->screen_name}}</a>
                                </td>
                                <td>{{$follower->followers_count}}</td>
                                <td>{{$follower->friends_count}}</td>
                                <td>{{$follower->statuses_count}}</td>
                                <td>{{$follower->account_creation}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <script>
                        $('#followers').DataTable({
                            "language": {
                                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/German.json"
                            },
                            "order": [[4, 'desc']],
                            "pageLength": 10,
                            "lengthMenu": [5, 10, 25, 50, 75, 100]
                        });
                    </script>
                </div>

            </div>
        </div>
    </div>
@endsection
