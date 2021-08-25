<div class="modal" tabindex="-1" role="dialog" id="modal-add-pt-card">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="far fa-plus-square"></i> {{__('pt.card.add')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('public-transport.card.add')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{__('public-transport-card.type')}}</label>
                        <select class="form-control" name="description" required>
                            <option value="">{{__('general.form.choose')}}</option>
                            <option>{{__('public-transport-card')}} 25</option>
                            <option>{{__('public-transport-card')}} 50</option>
                            <option>{{__('public-transport-card')}} 100</option>
                            <option>{{__('public-transport-card.student')}}</option>
                            <option>{{__('public-transport-card.other')}}</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>{{__('valid-from')}}</label>
                            <input type="date" name="valid_from" class="form-control" required/>
                        </div>
                        <div class="col-md-6">
                            <label>{{__('valid-until')}}</label>
                            <input type="date" name="valid_to" class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>{{__('cost-of-card')}}</label>
                        <input type="number" step="0.01" name="cost" class="form-control" required/>
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