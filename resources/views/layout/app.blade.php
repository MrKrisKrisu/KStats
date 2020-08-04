<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{__('KStats')}}</title>

    <link rel="stylesheet" href="/css/app.css"/>

    <script src="/js/app.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>

<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="/">{{__('KStats')}}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">

            @guest
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                </ul>
                <ul class="navbar-nav mr-right">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                </ul>
            @else
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Spotify
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('spotify') }}">Meine Statistik</a>
                            <a class="dropdown-item" href="{{ route('spotify.topTracks') }}">Meine TopTracks</a>
                            <a class="dropdown-item" href="{{ route('spotify.history') }}">Musikverlauf</a>
                            <a class="dropdown-item" href="{{ route('spotify.lostTracks') }}">Verschollene Tracks</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('rewe') }}">{{ __('REWE eBon Analyzer') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('twitter') }}">{{ __('Twitter') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('crowdsourcing_rewe') }}">{{ __('Crowdsourcing') }}</a>
                    </li>
                </ul>
                <ul class="navbar-nav mr-right">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->username }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('settings') }}">{{ __('Settings') }}</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>


                        </div>
                    </li>


                </ul>
            @endguest
        </div>
    </nav>
</header>
<div id="dashboard">
    <main role="main">
        @yield('jumbotron')

        <div class="container" id="container_main">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">@yield('title', 'KStats')</h1>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

    </main>

    <footer class="text-muted">
        <div class="container">
            <hr/>
            <p class="float-left">
                <a href="https://github.com/MrKrisKrisu/KStats/issues/new?labels=bug" target="ghub"
                   style="color: #E70000;">{{ __('general.report_bug') }}</a> |
                <a href="https://github.com/MrKrisKrisu/KStats/issues/new?labels=enhancement" target="ghub">{{ __('general.suggestion') }}</a> |
                <a href="https://github.com/MrKrisKrisu/KStats/" target="ghub">{{ __('general.show_sourcecode') }}</a>
            </p>
            <p class="float-right">
                <a href="/imprint/">{{ __('general.imprint') }}</a> |
                <a href="/disclaimer/">{{ __('general.disclaimer') }}</a> |
                <a href="#">{{ __('general.back_to_top') }}</a>
            </p>
        </div>
    </footer>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
@yield('javascript')
</html>
