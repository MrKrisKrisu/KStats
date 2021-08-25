<div class="modal" tabindex="-1" role="dialog" id="modal-add-pt-complaint">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="far fa-plus-square"></i> {{__('pt.complaint.add')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('public-transport.complaint.add')}}">
                @csrf
                <input type="hidden" name="card_id" id="public_transport_card_id_complaint"/>
                <input type="hidden" name="journey_id" id="journey_id_complaint"/>

                <div class="modal-body">
                    <div class="form-group">
                        <label>{{__('description')}}</label>
                        <textarea rows="3" name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{__('cashback')}}</label>
                        <input type="number" name="cashback" class="form-control" required placeholder="0.00"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('abort')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>