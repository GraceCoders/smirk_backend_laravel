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

                        </div>
                        <h5 class="card-title">Report User</h5>

                        <div class="table-responsive">

                            <table id="card-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Report On</th>
                                        <th>Report</th>
                                        <th>Report By</th>
                                    </tr>
                                </thead>
                                <tbody id="data">
                                    @foreach ($data as $value)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $value['user']->full_name }}</td>
                                            <td>{{ $value->report }}
                                            <td>{{ $value['reportBy']->full_name }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $data->links('pagination::bootstrap-4') }}
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

</script>
