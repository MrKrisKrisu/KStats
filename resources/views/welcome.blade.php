@extends('layout.cover')

@section('content')
    <main role="main" class="inner cover">
        <h1 class="cover-heading">KStats - {{__('general.menu.stats')}}</h1>
        <p class="lead">{{__('general.intro')}}</p>

        <div class="alert alert-danger">
            <b>Discontinued project</b>
            <p>This project is no longer maintained, new registrations are disabled.</p>

            <p>You can use the source code to host your own instance of KStats or fork the project on GitHub.</p>

            <a class="btn btn-success btn-sm" href="https://github.com/MrKrisKrisu/KStats" target="github">
                <i class="fab fa-github"></i>
                Show source code
            </a>
        </div>
    </main>
@endsection
