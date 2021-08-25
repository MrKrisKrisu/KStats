<div class="modal" tabindex="-1" role="dialog" id="modal-show-calc-{{$card->id}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-calculator"></i> {{__('cost-calculation')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td class="font-weight-bold">{{__('actual-costs')}}</td>
                        <td>{{number_format($card->cost, 2, ',', '.')}} €</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">{{__('value-of-journeys')}}</td>
                        <td>- {{number_format($card->journeys->sum('price_without_card'), 2, ',', '.')}} €</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">{{__('cashback-from-complaints')}}</td>
                        <td>- {{number_format($card->complaints->sum('price_without_card'), 2, ',', '.')}} €</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">{{__('effective-costs')}}</td>
                        <td class="text-primary font-weight-bold">
                            = {{ number_format(\App\Http\Controllers\Backend\PublicTransport\CostController::getEffectiveCosts($card), 2,',', '.')}}
                            €
                        </td>
                    </tr>

                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('close')}}</button>
            </div>
        </div>
    </div>
</div>