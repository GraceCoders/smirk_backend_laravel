@extends('layout.main')
@section('content')
<style>
    input#search {
    padding: 6px;
    border: black;
    border: 1px solid;
    border-radius: 6px;
    width: 187px;
}
    </style>
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
                        <input type="text" name="search" id="search" placeholder="Search Card" class="matchedit">

                            <table id="card-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Card</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                    @foreach ($card as $value)
                                    <tr>
                                        <td><img src="{{ asset('storage/' . $value->card_image) }}" style="height: 100px; width:100px"></td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->cat_name}}</td>
                                        <td>
                                        <a href="{{route('cards.edit',$value->id)}}">
                                            <i class="fa fa-edit mr-2" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('cards.delete', $value->id) }}">
                                            <i class="fa fa-trash mr-2" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    </tr>
                                    @endforeach
                                 

                                </tbody>
                            </table>
                            {{$card->links('pagination::bootstrap-4')}}

                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
@stop
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script>
    $(document).ready(function(){
        $('.matchedit').on('input', function postinput() {

        var search = $(this).val(); 
        $.ajax({ 
            url: '{{route("cards.search")}}',
            data: { search: search },
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
            type: 'POST'
        }).done(function(responseData) {   
            // HoldOn.close();
                    var data = JSON.parse(responseData);
                    var td = "";
                    for (var i = 0; i < data.length; i++) {
                        if (window.location == "http://127.0.0.1:8000/cards/list") {
                            var edit = "http://127.0.0.1:8000/cards/edit/" + data[i].id;
                            var del = "http://127.0.0.1:8000/cards/delete/" + data[i].id;
                            var image =  "http://127.0.0.1:8000/storage/" + data[i].card_image;
                        } else {
                            var edit = "https://smirkapp.us/cards/edit/" + data[i].id;
                            var del = "https://smirkapp.us/public/cards/delete/" + data[i].id;
                            var image =  "https://smirkapp.us/storage/" + data[i].card_image;

                        }
                        td += '<tr><td><img src="'+image+'" style="height: 100px; width:100px"></td><td>' + data[i].name + '</td><td>' + data[i].cat_name + '</td><td><a href="' + edit + '"><i class="fa fa-edit mr-2" aria-hidden="true"></i></a><a href="' + del + '"><i class="fa fa-trash mr-2" aria-hidden="true"></i></td></tr>';    
                    }
                    $('#data').html(td);
        });
    });
}); 
    </script>
