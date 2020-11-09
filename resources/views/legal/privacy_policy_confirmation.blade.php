@if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->privacy_confirmed_at === null)
    <hr/>
    <div class="card border-info mb-3">
        <div class="card-body text-info">
            <h5 class="card-title">Datenschutzerklärung bestätigen</h5>
            <p class="card-text">Bitte lese dir diese Datenschutzerklärung genau durch. Wenn du mit ihr einverstanden
                bist
                musst du zustimmen, um KStats zu verwenden.</p>
            <form method="POST" action="{{route('legal.privacy_policy.confirm')}}">
                @csrf
                <button class="btn btn-success">Datenschutzerklärung bestätigen</button>
            </form>
        </div>
    </div>
    <hr/>
@endif