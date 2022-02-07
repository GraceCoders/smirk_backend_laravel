@extends('layout.main')
@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Library</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <a href="{{ route('cards.add') }}"><button class="btn btn-outline-success ml-3 float-right"
                                    aria-disabled="true">Add</button></a>
                        </div>
                        <h5 class="card-title">Cards</h5>

                        <div class="table-responsive">
                            <table id="card-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Card</th>
                                        <th>Show Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($card as $value)
                                    <tr>
                                        <td><img src="{{$value->card_image}}" style="height: 100px; width:100px"></td>
                                        <td>{{$value->title}}</td>
                                        <td>Action</td>
                                    </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{$card->links()}}
        </div>
    </div>
@stop
