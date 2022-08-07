<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

        <title>{{__('shared-link.title', ['name' => $user->username])}} - KStats</title>

        <link rel="stylesheet" href="{{ mix('css/app.css') }}"/>

        <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

        <link rel="shortcut icon" type="image/x-icon" href="/favicon.svg"/>
    </head>

    <body>
        <div id="dashboard">
            <main role="main">
                <div class="container" id="container_main">

                    <div class="row justify-content-center">
                        <div class="col-12 mt-3 mb-3">
                            <h1>
                                <i class="fa-solid fa-music"></i>
                                {{__('shared-link.title', ['name' => $user->username])}}
                            </h1>

                            <div class="d-grid gap-2">
                                <a class="btn btn-lg btn-primary" href="{{route('shared-links')}}" target="_blank">
                                    <i class="fa-solid fa-arrow-pointer"></i>
                                    {{__('create-own-shared-link')}}
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h2>
                                        <i class="fa-solid fa-ranking-star"></i>
                                        {{__('top-tracks-days', ['days' => $sharedLink->spotify_days])}}
                                    </h2>
                                    <hr/>
                                    @foreach($topTracks as $playActivity)
                                        @include('spotify.components.track', [
                                            'track' => $playActivity->track,
                                            'showIteration' => true,
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="text-muted">
                <div class="container">
                    <hr/>
                    <p class="float-start">
                        <a href="https://github.com/MrKrisKrisu/KStats/issues/new?labels=bug" target="ghub"
                           style="color: #E70000;">{{ __('general.report_bug') }}</a> |
                        <a href="https://github.com/MrKrisKrisu/KStats/issues/new?labels=enhancement"
                           target="ghub">{{ __('general.suggestion') }}</a> |
                        <a href="https://github.com/MrKrisKrisu/KStats/"
                           target="ghub">{{ __('general.show_sourcecode') }}</a>
                        <br/>
                        <small>
                            Version:
                            <a href="https://github.com/MrKrisKrisu/KStats/commit/{{\App\Http\Controllers\HomeController::getCurrentGitHash()}}"
                               style="color: #6c757d;">
                                {{\App\Http\Controllers\HomeController::getCurrentGitHash()}}
                            </a>
                        </small>
                    </p>
                    <p class="float-end">
                        <a href="/imprint">{{ __('general.menu.imprint') }}</a> |
                        <a href="/privacy">{{ __('general.menu.privacy_policy') }}</a> |
                        <a href="#">{{ __('general.back_to_top') }}</a>
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
