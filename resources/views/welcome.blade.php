<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>KStats - {{__('general.menu.stats')}}</title>

        <link href="{{mix('/css/app.css')}}" rel="stylesheet">
        <link href="{{mix('/css/cover.css')}}" rel="stylesheet">

        <link rel="shortcut icon" type="image/x-icon" href="/favicon.svg">

        <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900"
              rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i"
              rel="stylesheet">
    </head>

    <body>

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
            <div class="container">
                <a class="navbar-brand" href="/">KStats</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                        aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{__('auth.register')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{__('auth.login')}}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">{{__('general.menu.dashboard')}}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <header class="masthead text-center text-white">
            <div class="masthead-content">
                <div class="container">
                    <h1 class="masthead-heading mb-0">KStats</h1>
                    <h2 class="masthead-subheading mb-0">
                        <span style="color: #1DB954;">Spotify</span>,
                        <span style="color: #1DA1F2;">Twitter</span>
                        and
                        <span>Shopping</span>
                        stats
                    </h2>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-xl rounded-pill mt-5">
                        {{__('general.to_stats')}}
                    </a>
                </div>
            </div>
        </header>

        <section>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="p-5">
                            <img class="img-fluid rounded-circle" src="/img/charts/spotify_daytime.png" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="p-5">
                            <h2 class="display-4">Spotify</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod aliquid, mollitia odio
                                veniam sit iste esse assumenda amet aperiam exercitationem, ea animi blanditiis
                                recusandae! Ratione voluptatum molestiae adipisci, beatae obcaecati.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="p-5">
                            <img class="img-fluid rounded-circle" src="/img/charts/spotify_daytime.png" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <h2 class="display-4">REWE</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod aliquid, mollitia odio
                                veniam sit iste esse assumenda amet aperiam exercitationem, ea animi blanditiis
                                recusandae! Ratione voluptatum molestiae adipisci, beatae obcaecati.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="p-5">
                            <img class="img-fluid rounded-circle" src="/img/charts/spotify_daytime.png" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="p-5">
                            <h2 class="display-4">Twitter</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod aliquid, mollitia odio
                                veniam sit iste esse assumenda amet aperiam exercitationem, ea animi blanditiis
                                recusandae! Ratione voluptatum molestiae adipisci, beatae obcaecati.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="py-5 bg-black">
            <div class="container text-white">
                <p class="m-0 text-center text-white small">
                    Version:
                    <a href="https://github.com/MrKrisKrisu/KStats/commit/{{\App\Http\Controllers\HomeController::getCurrentGitHash()}}"
                       style="color: #6c757d;" class="text-white">
                        {{\App\Http\Controllers\HomeController::getCurrentGitHash()}}
                    </a>
                </p>
                <hr/>
                <p class="float-left">
                    <a href="https://github.com/MrKrisKrisu/KStats/issues/new?labels=bug" target="ghub"
                       class="text-white">{{ __('general.report_bug') }}</a> |
                    <a href="https://github.com/MrKrisKrisu/KStats/issues/new?labels=enhancement" class="text-white"
                       target="ghub">{{ __('general.suggestion') }}</a> |
                    <a href="https://github.com/MrKrisKrisu/KStats/" class="text-white"
                       target="ghub">{{ __('general.show_sourcecode') }}</a>
                    <br/>
                    <small>

                    </small>
                </p>
                <p class="float-right">
                    <a href="/imprint" class="text-white">{{ __('general.menu.imprint') }}</a> |
                    <a href="/privacy" class="text-white">{{ __('general.menu.privacy_policy') }}</a> |
                    <a href="#" class="text-white">{{ __('general.back_to_top') }}</a>
                </p>
            </div>
        </footer>
    </body>
</html>
