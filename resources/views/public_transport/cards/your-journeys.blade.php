<div class="card">
    <div class="card-body">
        <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-add-pt-journey"
                onclick="$('#public_transport_card_id_journey').val({{$card->id}})">
            <i class="far fa-plus-square"></i> {{__('new-journey')}}
        </button>

        <h3><i class="fas fa-train"></i> {{__('your-journeys')}}</h3>

        @if($card->journeys->count() == 0)
            <span class="text-danger">{{__('no-journeys')}}</span>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{__('date')}}</th>
                        <th>{{__('origin')}}</th>
                        <th>{{__('destination')}}</th>
                        <th>{{__('saved')}}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($card->journeys->sortByDesc('created_at')->take(5) as $journey)
                        <tr>
                            <td>{{$journey->date->format('d.m.Y')}}</td>
                            <td>{{$journey->origin}}</td>
                            <td>{{$journey->destination}}</td>
                            <td>{{number_format($journey->saved, 2, ',', '.')}} €</td>
                            <td>
                                <button class="btn btn-primary btn-sm float-right" data-toggle="modal"
                                        data-target="#modal-add-pt-complaint"
                                        onclick="$('#public_transport_card_id_complaint').val({{$card->id}}); $('#journey_id_complaint').val({{$journey->id}});">
                                    <i class="fas fa-hand-holding-usd"></i> {{__('new-complaint')}}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>