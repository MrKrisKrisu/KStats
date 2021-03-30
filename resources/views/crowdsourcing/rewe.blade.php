@extends('layout.app')

@section('title')Crowdsourcing @endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('receipts.product_category')}}</h5>
                    @empty($categories_product)
                        <p class="text-danger">{{__('receipts.crowdsourcing.no_tasks')}}</p>
                    @else

                        <p>Du hast mal <b>"{{$categories_product->name}}"</b>
                            gekauft. {{__('receipts.crowdsourcing.question_category')}}
                            <small><i>{{__('receipts.last_bought')}} {{\Carbon\Carbon::parse($categories_product->lastReceipt)->diffForHumans()}}
                                    .</i></small>
                        </p>
                        <hr/>
                        <form method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="dropdown bootstrap-select">
                                    <select name="category_id" class="form-control" id="categories" required>
                                        <option value="">{{__('general.form.choose')}}</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">
                                                {{$category->parent->name}} -> {{$category->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" value="{{$categories_product->id}}">
                            <input type="hidden" name="action" value="setCategory"/>
                            <button type="submit" name="btn" value="save" class="btn btn-success">{{__('general.save')}}
                            </button>
                            <button type="submit" name="btn" value="ka"
                                    class="btn btn-danger">{{__('general.idk')}}</button>
                        </form>
                    @endif
                </div>
            </div>

            @if(count($lastCategories) > 0)
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>{{__('receipts.product')}}</th>
                                <th>{{__('receipts.product_category')}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lastCategories as $c)
                                <tr>
                                    <td>{{$c->product->name ?? '?'}}</td>
                                    <td>{{$c->category->name ?? '?'}}</td>
                                    <td>
                                        <form method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{$c->product->id}}"/>
                                            <button type="submit" class="btn btn-sm btn-danger" name="action"
                                                    value="deleteCategory"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{__('receipts.vegetarian')}}</h5>
                    @empty($vegetarian_product)
                        <p class="text-danger">{{__('receipts.crowdsourcing.no_tasks')}}</p>
                    @else
                        <p>Du hast mal <b>"{{$vegetarian_product->name}}"</b> gekauft. Ist es vegetarisch?<br>
                            <small><i>Zuletzt
                                    gekauft {{\Carbon\Carbon::parse($vegetarian_product->lastReceipt)->diffForHumans()}}
                                    .</i></small></p>
                        <hr/>
                        <form method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{$vegetarian_product->id}}"/>
                            <input type="hidden" name="action" value="setVegetarian"/>
                            <button type="submit" name="setVegetarian" value="1"
                                    class="btn btn-success">{{__('general.yes')}}</button>
                            <button type="submit" name="setVegetarian" value="0"
                                    class="btn btn-danger">{{__('general.no')}}</button>
                            <button type="submit" name="setVegetarian" value="-1"
                                    class="btn btn-dark">{{__('receipts.crowdsourcing.non_food')}}
                            </button>
                            <button type="submit" name="setVegetarian" value="ka"
                                    class="btn btn-warning">{{__('general.idk')}}
                            </button>
                        </form>
                    @endif
                </div>
            </div>


            @if(count($lastVegetarians) > 0)
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>{{__('receipts.product')}}</th>
                                <th>{{__('receipts.vegetarian')}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lastVegetarians as $c)
                                <tr>
                                    <td>{{$c->product->name}}</td>
                                    <td>{{$c->vegetarian === null ? '?' : str_replace(array('-1', '0', '1'), array('Kein Lebensmittel', 'Nein', 'Ja'), $c->vegetarian)}}</td>
                                    <td>
                                        <form method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{$c->product->id}}"/>
                                            <button type="submit" class="btn btn-sm btn-danger" name="action"
                                                    value="deleteVegetarian"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        $('#categories').select2({
            closeOnSelect: true,
            theme: 'bootstrap4',
        });
    </script>
@endsection

