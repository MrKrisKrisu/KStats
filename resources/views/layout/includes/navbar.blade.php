<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">{{__('KStats')}}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                @guest
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="/">{{__('general.menu.home')}}</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mr-right">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                            </li>
                        @endif
                    </ul>
                @else
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('home') }}">{{__('general.menu.dashboard')}}</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Spotify
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('spotify') }}">
                                    {{__('spotify.statistic')}}
                                </a>
                                <a class="dropdown-item" href="{{ route('spotify.topTracks') }}">
                                    {{__('spotify.title.top_tracks')}}
                                </a>
                                <a class="dropdown-item" href="{{ route('spotify.history') }}">
                                    {{__('spotify.title.history')}}
                                </a>
                                <a class="dropdown-item" href="{{ route('spotify.lostTracks') }}">
                                    {{__('spotify.title.lost_tracks')}}
                                </a>
                                <a class="dropdown-item" href="{{ route('spotify.mood-o-meter') }}">
                                    {{__('spotify.title.mood_o_meter')}}
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rewe') }}">{{ __('general.menu.receipts') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('twitter') }}">Twitter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('crowdsourcing_rewe') }}">{{ __('general.menu.crowdsourcing') }}</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mr-right">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->username }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item"
                                   href="{{ route('settings') }}">{{ __('settings.settings') }}</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('auth.logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                @endguest
            </div>
        </div>
    </nav>
</header>