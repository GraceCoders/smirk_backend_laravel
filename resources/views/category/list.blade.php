@extends('layout.main')
@section('content')
    @php
    $i = 1;
    @endphp
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
                    <div class="card-body">
                        <div class="">
                            <a href="{{ route('category.list.create') }}"><button
                                    class="btn btn-outline-success ml-3 float-right" aria-disabled="true">Add</button></a>
                        </div>
                        <h5 class="card-title">Category</h5>

                        <div class="table-responsive">
                        <input type="text" name="search" id="search" placeholder="Search Card" class="matchedit">

                            <table id="card-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                    @foreach ($card as $value)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td><a href="{{ route('category.inactive', $value->id) }}" method="POST">
                                                    <i class="fa fa-trash mr-2" aria-hidden="true"></i>
                                                </a></td>
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
    setTimeout(function() {
        $('.alert-block').remove();
    }, 5000);

    $(document).ready(function(){
        $('.matchedit').on('input', function postinput() {

        var search = $(this).val(); 
        $.ajax({ 
            url: '{{route("category.search")}}',
            data: { search: search },
              headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
            type: 'POST'
        }).done(function(responseData) {   
            // HoldOn.close();
                    var data = JSON.parse(responseData);
                    var td = "";
                    var id = 1;
                    for (var i = 0; i < data.length; i++) {
                        if (window.location == "http://127.0.0.1:8000/catgory/list") {
                            var del = "http://127.0.0.1:8000/catgory/delete/" + data[i].id;
                        } else {
                            var del = "https://smirkapp.us/public/catgory/delete/" + data[i].id;
                        }
                        td += '<tr><td>' + id++ + '</td><td>' + data[i].name + '</td><td><a href="' + del + '"><i class="fa fa-trash mr-2" aria-hidden="true"></i></td></tr>';    
                    }
                    $('#data').html(td);
        });
    });
}); 
    </script>

