@extends('layout.app')

@section('title', 'Twitter')

@section('content')

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('twitter.your_profile')}}</h5>
                    <table class="table">
                        <tr>
                            <td>{{__('twitter.displayname')}}</td>
                            <td>{{$twitter_profile->name}}</td>
                        </tr>
                        <tr>
                            <td>{{__('twitter.username')}}</td>
                            <td>{{'@'.$twitter_profile->screen_name}}</td>
                        </tr>
                        <tr>
                            <td>{{__('twitter.follower')}}</td>
                            <td>{{$twitter_profile->followers_count}}</td>
                        </tr>
                        <tr>
                            <td>{{__('twitter.friends')}}</td>
                            <td>{{$twitter_profile->friends_count}}</td>
                        </tr>
                        <tr>
                            <td>{{__('twitter.tweets')}}</td>
                            <td>{{$twitter_profile->statuses_count}}</td>
                        </tr>
                    </table>
                </div>

            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('twitter.unfollower')}}</h5>

                    @if(count($twitter_profile->unfollower)  > 0)
                        <table class="table" id="unfollowers">
                            <thead>
                                <tr>
                                    <th>{{__('twitter.username')}}</th>
                                    <th>{{__('twitter.unfollowing_time')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($twitter_profile->unfollower as $unfollower)
                                    @isset($unfollower->unfollower_profile)
                                        <tr>
                                            <td>{{$unfollower->unfollower_profile->screen_name ?? $unfollower->unfollower_profile->id}}</td>
                                            <td data-order="{{$unfollower->unfollowed_at}}">{{$unfollower->unfollowed_at?->diffForHumans()}}</td>
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
                        <p>{{__('twitter.no_unfollower')}}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('twitter.follower')}}</h5>
                    <table class="table table-striped table-hover table-responsive" id="followers">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>{{__('twitter.username')}}</th>
                                <th>{{__('twitter.follower')}}</th>
                                <th>{{__('twitter.friends')}}</th>
                                <th>{{__('twitter.tweets')}}</th>
                                <th>{{__('twitter.created_at')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($twitter_profile->followers as $follower)
                                <tr>
                                    <td>
                                        @isset($follower->profile_image_url)
                                            <img src="{{$follower->profile_image_url}}" style="max-height: 30px;"/>
                                        @endisset
                                    </td>
                                    <td>
                                        @if($follower->protected)
                                            <i class="fas fa-lock"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="https://twitter.com/{{$follower->screen_name}}" target="_blank">
                                            {{'@'.$follower->screen_name}}
                                        </a>
                                    </td>
                                    <td data-order="{{$follower->followers_count}}">
                                        {{number_format($follower->followers_count, 0, ',','.')}}
                                    </td>
                                    <td data-order="{{$follower->friends_count}}">
                                        {{number_format($follower->friends_count, 0, ',','.')}}
                                    </td>
                                    <td data-order="{{$follower->statuses_count}}">
                                        {{number_format($follower->statuses_count, 0, ',','.')}}
                                    </td>
                                    <td data-order="{{$follower->account_creation}}">
                                        {{$follower->account_creation->format('d.m.Y H:i')}}
                                    </td>
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

            <div class="card">
                <div class="card-body">
                    <canvas id="chart_followers"></canvas>
                    <script>
                        window.onload = function () {
                            window.myLine = new Chart(document.getElementById('chart_followers').getContext('2d'), {
                                type: 'line',
                                data: {
                                    datasets: [
                                        {
                                            label: 'Follower',
                                            data: [
                                                    @foreach($twitter_profile->dailies as $daily)
                                                {
                                                    x: new Date({{$daily->date->year}}, {{$daily->date->month - 1}}, {{$daily->date->day}}),
                                                    y: {{$daily->follower_count}}
                                                }
                                                @if(!$loop->last) , @endif
                                                @endforeach
                                            ],
                                            backgroundColor: '#38a3a6',
                                            borderColor: '#38a3a6',
                                            fill: false
                                        }, {
                                            label: 'Folge ich',
                                            data: [
                                                    @foreach($twitter_profile->dailies as $daily)
                                                {
                                                    x: new Date({{$daily->date->year}}, {{$daily->date->month - 1}}, {{$daily->date->day}}),
                                                    y: {{$daily->friends_count}}
                                                }
                                                @if(!$loop->last) , @endif
                                                @endforeach
                                            ],
                                            backgroundColor: '#592A0C',
                                            borderColor: '#592A0C',
                                            fill: false
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    hover: {
                                        mode: 'nearest',
                                        intersect: true
                                    },
                                    scales: {
                                        xAxes: [{
                                            display: true,
                                            type: 'time',
                                            scaleLabel: {
                                                display: true,
                                                labelString: 'Month'
                                            }
                                        }],
                                        yAxes: [{
                                            display: true,
                                            scaleLabel: {
                                                display: true,
                                                labelString: 'Value'
                                            }
                                        }]
                                    }
                                }
                            });
                        };
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
