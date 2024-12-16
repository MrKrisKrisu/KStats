<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <title>KStats - {{__('general.menu.stats')}}</title>

    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/cover.css" rel="stylesheet">

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.svg">
</head>

<body class="text-center">

<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
    <header class="masthead mb-auto">
        <div class="inner">
            <h3 class="masthead-brand">KStats</h3>
            <nav class="nav nav-masthead justify-content-center">
                <a class="nav-link active" href="{{ route('welcome') }}">{{__('general.menu.home')}}</a>
                @guest
                    <a class="nav-link" href="{{ route('login') }}">{{__('auth.login')}}</a>
                    @if (Route::has('register'))
                        <a class="nav-link" href="{{ route('register') }}">{{__('auth.register')}}</a>
                    @endif
                @else
                    <a class="nav-link" href="{{ route('home') }}">{{__('general.menu.dashboard')}}</a>
                @endif
            </nav>
        </div>
    </header>

    @yield('content')

    <footer class="mastfoot mt-auto">
        <div class="inner">
            <p>
                <a href="https://github.com/MrKrisKrisu/KStats"
                   target="github">{{ __('general.show_sourcecode') }}</a> -
                <a href="/imprint/">{{__('general.menu.imprint')}}</a> -
                <a href="/privacy">{{ __('general.menu.privacy_policy') }}</a>
            </p>
        </div>
    </footer>
</div>
</body>
</html>
