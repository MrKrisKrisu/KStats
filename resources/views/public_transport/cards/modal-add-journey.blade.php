<div class="modal" tabindex="-1" role="dialog" id="modal-add-pt-journey">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="far fa-plus-square"></i> {{__('pt.journey.add')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('public-transport.journey.add')}}">
                @csrf
                <input type="hidden" name="public_transport_card_id" id="public_transport_card_id_journey"
                       value="0"/>

                <div class="modal-body">

                    <div class="form-group">
                        <label>{{__('date')}}</label>
                        <input type="date" name="date" class="form-control" required value="{{date('Y-m-d')}}"/>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6">
                            <label>{{__('origin')}}</label>
                            <input type="text" name="origin" class="form-control" required
                                   placeholder="{{\Faker\Factory::create('de_DE')->city}} Hbf"/>
                        </div>
                        <div class="col-md-6">
                            <label>{{__('destination')}}</label>
                            <input type="text" name="destination" class="form-control" required
                                   placeholder="{{\Faker\Factory::create('de_DE')->city}} Hbf"/>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6">
                            <label>{{__('price-without-card')}}</label>
                            <input type="number" step="0.01" name="price_without_card" class="form-control" required
                                   value="0.00"/>
                        </div>
                        <div class="col-md-6">
                            <label>{{__('price-with-card')}}</label>
                            <input type="number" step="0.01" name="price_with_card" class="form-control" required
                                   value="0.00"/>
                        </div>
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