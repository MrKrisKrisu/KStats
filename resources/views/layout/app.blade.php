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
        @include('layout.includes.navbar')
        <div id="dashboard">
            <main role="main">
                @yield('before-container')

                <div class="container" id="container_main">
                    @hasSection('title')
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 id="mainTitle">@yield('title')</h1>
                        </div>
                    @else
                        <div style="margin-top: 25px;"></div>
                    @endif

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
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
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
                    <p class="float-left">
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
                    <p class="float-right">
                        <a href="/imprint">{{ __('general.menu.imprint') }}</a> |
                        <a href="/privacy">{{ __('general.menu.privacy_policy') }}</a> |
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
