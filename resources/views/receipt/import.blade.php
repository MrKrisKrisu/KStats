@extends('layout.app')

@section('title', 'Kassenzettel hochladen')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{route('receipt.import.upload')}}">
                        @csrf

                        <div class="form-group">
                            <label>Markt auswählen</label>
                            <select class="form-control" disabled>
                                <option>REWE</option>
                            </select>
                            <small class="text-muted">Aktuell wird nur der Supermarkt REWE unterstützt.</small>
                        </div>

                        <div class="form-group">
                            <label>Kassenzettel <small>(.pdf)</small></label>
                            <input id="file" type="file" class="form-control" name="file" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Hochladen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
