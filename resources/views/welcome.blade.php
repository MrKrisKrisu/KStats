@extends('layout.cover')

@section('content')
    <main role="main" class="inner cover">
        <h1 class="cover-heading">KStats - {{__('general.menu.stats')}}</h1>
        <p class="lead">{{__('general.intro')}}</p>
        <p class="lead">
            <a href="{{ route('home') }}" class="btn btn-lg btn-secondary">{{__('general.to_stats')}}</a>
        </p>
    </main>
@endsection
