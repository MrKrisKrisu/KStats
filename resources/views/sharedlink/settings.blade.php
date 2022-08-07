@extends('layout.app')

@section('title', __('shared-links'))

@section('content')
    <div class="row">
        @if($sharedLinks->count() === 0)
            <div class="col-md-12">
                <div class="alert alert-primary">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{__('no-shared-links')}}
                </div>
            </div>

            <div class="col-md-8"></div>
        @else
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>{{__('link')}}</th>
                                        <th>{{__('max-tracks')}}</th>
                                        <th>{{__('tracks-since')}}</th>
                                        <th>{{__('created_at')}}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sharedLinks as $sharedLink)
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)"
                                                   onclick="navigator.clipboard.writeText('{{$sharedLink->url}}'); notyf.success('{{__('copied')}}')">
                                                    {{__('copy-link')}}
                                                </a>
                                            </td>
                                            <td>{{$sharedLink->spotify_tracks}} Tracks</td>
                                            <td>{{$sharedLink->spotify_days}} {{__('days')}}</td>
                                            <td>{{$sharedLink->created_at->format(__('datetime-format'))}}</td>
                                            <td>
                                                <form method="POST" action="{{route('shared-links.delete')}}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$sharedLink->id}}">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa-solid fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="fs-5">
                        <i class="fa-solid fa-share-nodes"></i>
                        Statistiken teilen
                    </h2>

                    <form method="POST" action="{{route('shared-links.create')}}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="form-maxTracks" min="3" max="100"
                                   name="spotify_tracks"
                                   placeholder="{{__('max-tracks')}}" value="25"/>
                            <label for="form-maxTracks">{{__('max-tracks')}}</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="form-days" min="14" max="3650"
                                   name="spotify_days"
                                   placeholder="{{__('tracks-since')}}" value="365"/>
                            <label for="form-days">{{__('tracks-since')}}</label>
                        </div>

                        <button class="btn btn-outline-success float-end">
                            <i class="fa-solid fa-share-nodes"></i>
                            Teilen
                        </button>
                    </form>
                </div>
                <div class="card-footer">
                    <small>
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        {{__('shared-links.note')}}
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection
