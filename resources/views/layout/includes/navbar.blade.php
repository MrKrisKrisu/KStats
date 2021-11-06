<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="far fa-chart-bar"></i> KStats</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                @guest
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="/">{{__('general.menu.home')}}</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav me-right">
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
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="fas fa-home"></i> {{__('general.menu.dashboard')}}
                            </a>
                        </li>
                        @if(auth()->user()->socialProfile->isConnectedSpotify)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fab fa-spotify"></i> Spotify
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
                                    <a class="dropdown-item" href="{{ route('spotify.explore') }}">
                                        {{__('spotify.title.explore')}}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('spotify.friendship-playlists') }}">
                                        {{__('spotify.title.friendship-playlists')}}
                                    </a>
                                </div>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rewe') }}">
                                <i class="fas fa-shopping-cart"></i> {{ __('general.menu.receipts') }}
                            </a>
                        </li>
                        @if(auth()->user()->socialProfile->isConnectedTwitter)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('twitter') }}">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->reweReceipts()->count() > 0)
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="{{ route('crowdsourcing_rewe') }}">
                                    <i class="fas fa-magic"></i> {{ __('general.menu.crowdsourcing') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                    <ul class="navbar-nav me-right">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-user"></i> {{ Auth::user()->username }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('friendships') }}">
                                    <i class="fas fa-users"></i>
                                    Freunde
                                </a>
                                <a class="dropdown-item" href="{{ route('settings') }}">
                                    <i class="fas fa-cog"></i>
                                    {{ __('settings.settings') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
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