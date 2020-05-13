@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Produktkategorie</div>
                <div class="card-body">
                    @empty($categories_product)
                        <p style="color: #E70000; font-weight: bold;">Es sind aktuell keine Aufgaben für dich Verfügbar.
                            Komme gerne später wieder!</p>
                    @else

                        <p>Du hast mal <b>"{{$categories_product->name}}"</b> gekauft. Kannst du das bitte in eine
                            Kategorie
                            einordnen?</p>
                        <p>Zuletzt gekauft: {{$categories_product->lastReceipt}}</p>
                        <form method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Kategorie</label>
                                <div class="dropdown bootstrap-select">
                                    <select name="category_id">
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->parent->name}}
                                                -> {{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="product_id" value="{{$categories_product->id}}">
                            <input type="hidden" name="action" value="setCategory"/>
                            <button type="submit" name="btn" value="save" class="btn btn-success">Kategorie speichern
                            </button>
                            <button type="submit" name="btn" value="ka" class="btn btn-danger">Keine Ahnung</button>
                        </form>
                    @endif
                </div>
            </div>

            @if(count($lastCategories) > 0)
                <div class="card">
                    <div class="card-header">Deine letzten Eingaben</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Zugewiesene Kategorie</th>
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
                                                    value="deleteCategory"><i class="fa fa-trash"></i></button>
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
                <div class="card-header">Vegetarisch</div>
                <div class="card-body">
                    @empty($vegetarian_product)
                        <p style="color: #E70000; font-weight: bold;">Es sind aktuell keine Aufgaben für dich Verfügbar.
                            Komme gerne später wieder!</p>
                    @else
                        <p>Du hast mal <b>"{{$vegetarian_product->name}}"</b> gekauft. Ist es vegetarisch?</p>
                        <p>Zuletzt gekauft: {{$vegetarian_product->lastReceipt}}</p>
                        <form method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{$vegetarian_product->id}}"/>
                            <input type="hidden" name="action" value="setVegetarian"/>
                            <button type="submit" name="setVegetarian" value="1" class="btn btn-success">Ja</button>
                            <button type="submit" name="setVegetarian" value="0" class="btn btn-danger">Nein</button>
                            <button type="submit" name="setVegetarian" value="-1" class="btn btn-dark">Ist kein
                                Lebensmittel
                            </button>
                            <button type="submit" name="setVegetarian" value="ka" class="btn btn-warning">Keine Ahnung
                            </button>
                        </form>
                    @endif
                </div>
            </div>


            @if(count($lastVegetarians) > 0)
                <div class="card">
                    <div class="card-header">Deine letzten Eingaben</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Produkt</th>
                                <th>Vegetarisch?</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lastVegetarians as $c)
                                <tr>
                                    <td>{{$c->product->name}}</td>
                                    <td>{{$c->vegetarian === NULL ? '?' : str_replace(array('-1', '0', '1'), array('Kein Lebensmittel', 'Nein', 'Ja'), $c->vegetarian)}}</td>
                                    <td>
                                        <form method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{$c->product->id}}"/>
                                            <button type="submit" class="btn btn-sm btn-danger" name="action"
                                                    value="deleteVegetarian"><i class="fa fa-trash"></i></button>
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
@endsection