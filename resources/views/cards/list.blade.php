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
                        <a href="{{ route('cards.add') }}"><button class="btn btn-outline-success ml-3 float-right" aria-disabled="true">Add</button></a>
                    </div>
                    <h5 class="card-title">Cards</h5>

                    <div class="table-responsive">
                        <table id="card-table" class="table table-striped table-bordered carddatatable">
                            <thead>
                                <tr>
                                    <th>Card</th>
                                    <th>Name</th>
                                    <th>Category Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  

<script type="text/javascript">

    $(document).ready(function() {

        $(function() {

            var table = $('.carddatatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('cards.list') }}",
                columns: [{
                        data: 'image',
                        name: ''
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'cat_name',
                        name: 'cat_name'
                    },   
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });
        });
    });
</script>