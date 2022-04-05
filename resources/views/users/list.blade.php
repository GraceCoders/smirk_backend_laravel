@extends('layout.main')
@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Tables</h4>
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
                        <h5 class="card-title">Users</h5>
                        <div class="table-responsive">
                            <table id="users-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Phone No.</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Date Of Birth</th>
                                        <th>About</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="blockModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <input type="hidden" name="user_id" id="block_user_id" value="">
                <div class="modal-body">
                    <p>Are you Sure you want Block <input id="fullname">?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info " id="block_user">Confirm</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="unblockModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <input type="hidden" name="user_id" id="unblock_user_id" value="">
                <div class="modal-body">
                    <p>Are you Sure you want UnBlock <input id="fullname">?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info " id="unblock_user">Confirm</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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

    $(document).on("click", ".block-modal", function(e) {
        var delete_id = $(this).data('id');
        var name = $(this).data('name');
        $('#fullname').val(name);
        $('#block_user_id').val(delete_id);

        $('#blockModal').modal('show');
    });
    $(document).on("click", ".unblock-modal", function(e) {
        var delete_id = $(this).data('id');
        var name = $(this).data('name');
        $('#fullname').val(name);
        $('#block_user_id').val(delete_id);
        $('#unblockModal').modal('show');
    });
    $(document).on("click", "#block_user", function(e) {
        var user_id = $('#block_user_id').val();
        $.ajax({
            type: 'POST',
            data: {
                id: user_id
            },
            url:{{route('report.user.block')}}
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#blockModal').modal('hide');
                $('.ajax_meassage').append('<div class="alert alert-success message">' + data +
                    '</div>');
                $(".message").delay(4000).slideUp(300);
                var oTable = $('#userTable').dataTable();
                oTable.fnDraw(false);
            }
        });
    });
    $(document).on("click", "#unblock_user", function(e) {
        var user_id = $('#unblock_user_id').val();
        $.ajax({
            type: 'POST',
            data: {
                id: user_id
            },
            url:{{route('report.user.block')}}
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#blockModal').modal('hide');
                $('.ajax_meassage').append('<div class="alert alert-success message">' + data +
                    '</div>');
                $(".message").delay(4000).slideUp(300);
                var oTable = $('#userTable').dataTable();
                oTable.fnDraw(false);
            }
        });
    });
</script>

