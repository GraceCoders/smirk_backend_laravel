@extends('layout.main')
@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Add Card</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Card</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form class="form-horizontal col-6" action="{{ route('cards.add') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="card_image" class="col-sm-3 text-right control-label col-form-label">
                                    Icon :</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" id="card_image" name="card_image"
                                        placeholder="Card Image">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="card_image" class="col-sm-3 text-right control-label col-form-label">
                                        Show :</label>
                                <div class="col-sm-9">

                                    @php
                                    $data = DB::table('shows')->get();
                                    @endphp
                                    <select name="show_id" id="show_id">
                                        @foreach ($data  as $value)
                                        <option value="{{$value->id}}">{{$value->title}}</option>
                                        @endforeach
                                      </select>
                                </div>
                                </div>
                        </div>
                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
