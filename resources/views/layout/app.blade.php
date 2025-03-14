<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@hasSection('title')
            @yield('title') -
        @endif{{__('KStats')}}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}"/>

    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.svg">
</head>

<body>
@include('layout.includes.navbar')
<div id="dashboard">
    <main role="main">
        @yield('before-container')

        <div class="container" id="container_main">
            @yield('before-title')

            <div class="mt-3"></div>
            @hasSection('title')
                <h1 id="mainTitle">@yield('title')</h1>
                <hr/>
            @endif

            @auth
                @if(auth()->user()->socialProfile->spotify_user_id != null && !str_contains(auth()->user()->socialProfile->spotify_scopes, 'user-read-recently-played'))
                    <div class="alert alert-danger">
                        <b>{{__('spotify.error')}}</b>
                        <p>{{__('spotify.error.text')}}</p>
                        <a class="btn btn-success btn-sm" href="{{route('redirectProvider', 'spotify')}}">
                            <i class="fab fa-spotify"></i>
                            {{strtr(__('settings.third-party.connect-to'), [':thirdparty' => 'Spotify'])}}
                        </a>
                    </div>
                @endif
            @endauth

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">
                            {!! Session::get('alert-' . $msg) !!}
                        </p>
                    @endif
                @endforeach
            </div>

            @yield('content')
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
                @if(config('auth.registration_enabled'))
                    <a href="/imprint">{{ __('general.menu.imprint') }}</a> |
                    <a href="/privacy">{{ __('general.menu.privacy_policy') }}</a> |
                @endif
                <a href="#">{{ __('general.back_to_top') }}</a>
            </p>
        </div>
    </footer>
</div>
</body>
@yield('javascript')
@yield('footer')
@if(auth()->check() && auth()->user()->privacy_confirmed_at !== null)
    <script type="text/javascript">
        var _paq = window._paq = window._paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function () {
            var u = "//{{config('app.matomo.url')}}/";
            _paq.push(['setTrackerUrl', u + 'matomo.php']);
            _paq.push(['setSiteId', '{{config('app.matomo.id')}}']);
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript';
            g.async = true;
            g.src = u + 'matomo.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
    <noscript><p><img src="//{{config('app.matomo.url')}}/matomo.php?idsite={{config('app.matomo.id')}}&amp;rec=1"
                      style="border:0;" alt=""/></p></noscript>
@endif
</html>
