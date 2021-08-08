@extends('layout.app')

@section('title', __('settings.settings'))

@section('content')
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-6">
            @include('settings.cards.third-party')
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('email.your')}}</h5>
                    @if(count($emails) == 0)
                        <p class="text-danger">{{__('email.nodata')}}</p>
                    @else
                        <p>{{__('email.list')}}</p>
                        <table class="table">
                            <tbody>
                                @foreach($emails as $email)
                                    <tr>
                                        <td>{{$email->email}}</td>
                                        <td>
                                            @if($email->verified_user_id !== null)
                                                <span style="color: green;">{{__('verified')}}</span>
                                            @else
                                                <span style="color: #E70000;">{{__('unverified')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST" action="{{route('settings.delete.email')}}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$email->id}}"/>
                                                <button type="submit" class="btn btn-sm btn-danger"><i
                                                            class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    <hr/>
                    <h6>{{__('auth.email')}}</h6>
                    <form method="POST" action="{{route('settings.save.email')}}">
                        @csrf
                        <div class="form-group">
                            <input type="email" name="email" placeholder="{{__('auth.email')}}" class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                    </form>
                    <hr/>
                    <small><b>{{__('email.why')}}</b> {{__('email.why.explain')}}</small>
                </div>
            </div>

            <div class="card">

                <div class="card-body">
                    <h5 class="card-title">{{__('settings.password.change')}}</h5>
                    <form method="POST" action="{{route('settings.user.password.change')}}">
                        @csrf
                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{__('settings.password.current')}}</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" name="current_password"
                                       autocomplete="current-password" required/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{__('settings.password.new')}}</label>

                            <div class="col-md-8">
                                <input id="new_password" type="password" class="form-control" name="new_password"
                                       autocomplete="current-password" required/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{__('settings.password.new_repeat')}}</label>

                            <div class="col-md-8">
                                <input id="new_confirm_password" type="password" class="form-control"
                                       name="new_confirm_password" autocomplete="current-password" required/>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('settings.set_language')}}</h5>

                    <form method="POST" action="{{route('settings.set.lang')}}">
                        @csrf
                        <div class="form-group">
                            <select name="locale" class="form-control" required>
                                <option value="">{{__('settings.select')}}</option>
                                <option value="de"
                                        @if($user->locale == 'de') selected @endif>{{__('settings.lang.de')}}</option>
                                <option value="en"
                                        @if($user->locale == 'en') selected @endif>{{__('settings.lang.en')}}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

