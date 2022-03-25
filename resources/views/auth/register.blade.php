@extends('layout.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="card-body">
                    <h5 class="card-title">{{ __('auth.register') }}</h5>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-2 row">
                            <label for="name"
                                   class="col-md-4 col-form-label text-md-right">{{ __('auth.username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text"
                                       class="form-control @error('username') is-invalid @enderror" name="username"
                                       value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <label for="email"
                                   class="col-md-4 col-form-label text-md-right">{{ __('auth.email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror" name="email"
                                       value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{ __('auth.password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                {{ __('auth.pw_confirm') }}
                            </label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <hr/>

                        <div class="mb-2 row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                Captcha
                            </label>
                            <div class="col-md-6">
                                <p>{!! captcha_img() !!}</p>
                                <input type="text" class="form-control" name="captcha" placeholder="Captcha eingeben"/>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('auth.register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
