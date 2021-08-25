<div class="card">
    <div class="card-body">
        <h3><i class="far fa-credit-card"></i> {{$card->description}}</h3>
        <span class="text-center">{{$card->valid_from->format('d.m.Y')}} - {{$card->valid_to->format('d.m.Y')}}</span>
        <hr/>

        <div class="text-center">
            <span class="text-primary" style="font-size: 1.5em;">
                {{ number_format(\App\Http\Controllers\Backend\PublicTransport\CostController::getEffectiveCosts($card), 2,',', '.')}} €
            </span>
            <br/>
            <small>
                {{__('effective-costs')}}
            </small>
        </div>
        <hr/>
        <a class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modal-show-calc-{{$card->id}}">
            <i class="fas fa-calculator"></i> {{__('show-calculation')}}
        </a>


    </div>
</div>