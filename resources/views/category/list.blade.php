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
                            <a href="{{ route('category.list.create') }}"><button class="btn btn-outline-success ml-3 float-right"
                                    aria-disabled="true">Add</button></a>
                        </div>
                        <h5 class="card-title">Cards</h5>

                        <div class="table-responsive">
                            <table id="card-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($card as $value)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$value->name}}</td>
                                        <td><form action="{{route('category.list.destroy',$value->id)}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" onclick><i class="fa fa-trash mr-2" aria-hidden="true"></i>
                                            </button>
                                        </form></td>
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
<script>

setTimeout(function() {
    $('.alert-block').remove();
}, 5000);
</script>
