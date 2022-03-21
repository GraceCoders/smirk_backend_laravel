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
                            <a href="{{ route('shows.add') }}"><button class="btn btn-outline-success ml-3 float-right"
                                    aria-disabled="true">Add</button></a>
                        </div>
                        <h5 class="card-title">Shows</h5>

                        <div class="table-responsive">
                        <input type="text" name="search" id="search" placeholder="Search Card" class="matchedit">

                            <table id="show-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Icon</th>
                                        <th>Category Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                    @foreach ($show as $value)
                                        <tr>
                                            <td>{{ $value->title }}</td>
                                            <td><img src="{{ asset('storage/' . $value->show_icon) }}"
                                                    style="height: 100px; width:100px"></td>
                                            <td>{{ $value->name }}</td>
                                            @if ($value->status == 1)
                                                <td><button type="button" class="btn btn-primary">Active</button>
                                                </td>
                                            @else
                                                <td><button type="button" class="btn btn-secondary">Inactive</button>
                                                </td>
                                            @endif
                                            <td><a href="{{ route('shows.delete', $value->id) }}" method="POST">
                                                <i class="fa fa-trash mr-2" aria-hidden="true"></i>

                                                <a href="{{ route('shows.edit', $value->id) }}">
                                                <i class="fa fa-edit mr-2" aria-hidden="true"></i>
                                            </a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$show->links('pagination::bootstrap-4')}}
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
            url: '{{route("shows.search")}}',
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
                        if(data[i].status){
                 var button= '<button type="button" class="btn btn-primary">Active</button>';
                        }else{
                 var button = '<button type="button" class="btn btn-secondary">Inactive</button>';
                        }
                        if (window.location == "http://127.0.0.1:8000/shows/list") {
                            var edit = "http://127.0.0.1:8000/shows/edit/" + data[i].id;
                            var del = "http://127.0.0.1:8000/shows/delete/" + data[i].id;
                            var image =  "http://127.0.0.1:8000/storage/" + data[i].show_icon;
                        } else {
                            var edit = "https://smirkapp.us/shows/edit/" + data[i].id;
                            var del = "https://smirkapp.us/public/shows/delete/" + data[i].id;
                            var image =  "https://smirkapp.us/storage/" + data[i].show_icon;
                        }
                        td += '<tr><td>' + data[i].title + '</td><td><img src="'+image+'" style="height: 100px; width:100px"></td><td>' + data[i].name + '</td><td>'+button+'</td><td><a href="' + edit + '"><i class="fa fa-edit mr-2" aria-hidden="true"></i></a><a href="' + del + '"><i class="fa fa-trash mr-2" aria-hidden="true"></i></td></tr>';    
                    }
                    $('#data').html(td);
        });
    });
}); 
    </script>