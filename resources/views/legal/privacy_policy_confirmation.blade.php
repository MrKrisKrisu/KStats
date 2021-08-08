@if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->privacy_confirmed_at === null)
    <hr/>
    <div class="card border-info mb-3">
        <div class="card-body text-info">
            <h5 class="card-title">{{__('privacy-confirm')}}</h5>
            <p class="card-text">{{__('privacy-confirm.text')}}</p>
            <form method="POST" action="{{route('legal.privacy_policy.confirm')}}">
                @csrf
                <button class="btn btn-success">{{__('privacy-confirm')}}</button>
            </form>
        </div>
    </div>
    <hr/>
@endif