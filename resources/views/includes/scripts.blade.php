<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('dist/js/jquery.ui.touch-punch-improved.js') }}"></script>
<script src="{{ asset('dist/js/jquery-ui.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('dist/js/waves.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('dist/js/custom.min.js') }}"></script>
<!-- this page js -->
<script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/calendar/cal-init.js') }}"></script>
<script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
<script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
<script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    // ==============================================================
    // Login and Recover Password
    // ==============================================================
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    $('#to-login').click(function() {

        $("#recoverform").hide();
        $("#loginform").fadeIn();
    });
    var usersTable = jQuery('#users-table').DataTable({
        "pagingType": "numbers",
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "orderable": false,
        "ajax": {
            "url": "{{ route('users.list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function(data) {
                var searchData = $('input[type=search]').value;
                var statusData = $('input[type=radio]:checked').val();
                data.searchText = searchData;
                data.statusData = statusData;
                data._token = '{{ csrf_token() }}';
            }
        },
        columns: [{
                "data": "mobile"
            },
            {
                data: "full_name",
            },
            {
                data: "email",
            }, {
                data: "date_of_birth",
            }, {
                data: "about",
            },
        ],
    });
    var ethnicityTable = jQuery('#ethnicity-table').DataTable({
        "pagingType": "numbers",
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "orderable": false,
        "ajax": {
            "url": "{{ route('ethnicities.list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function(data) {
                var searchData = $('input[type=search]').value;
                var statusData = $('input[type=radio]:checked').val();
                data.searchText = searchData;
                data.statusData = statusData;
                data._token = '{{ csrf_token() }}';
            }
        },
        columns: [{
                "data": "title"
            },
            {
                data: "status",
                "render": function(data, type, row) {
                    if (row.status == '1') {
                        return 'Active';
                    } else {
                        return 'Deactive';
                    }
                }
            },
            {
                data: "id",
                "render": function(data, type, full, meta) {
                    return '<button type="submit" onClick="activateOrDeactivate(0,' + data +
                        ',2)"><i class="fa fa-trash mr-2" aria-hidden="true"></i></button>';
                }
            }
        ],
    });
    var preferenceTable = jQuery('#preference-table').DataTable({
        "pagingType": "numbers",
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "orderable": false,
        "ajax": {
            "url": "{{ route('preferences.list') }}",
            "dataType": "json",
            "type": "POST",
            "data": function(data) {
                var searchData = $('input[type=search]').value;
                var statusData = $('input[type=radio]:checked').val();
                data.searchText = searchData;
                data.statusData = statusData;
                data._token = '{{ csrf_token() }}';
            }
        },
        columns: [{
                "data": "title"
            },
            {
                data: "status",
                "render": function(data, type, row) {
                    if (row.status == '1') {
                        return 'Active';
                    } else {
                        return 'Deactive';
                    }
                }
            },
            {
                data: "id",
                "render": function(data, type, full, meta) {
                    return '<button type="submit" onClick="activateOrDeactivate(1,' + data +
                        ',2)"><i class="fa fa-trash mr-2 " aria-hidden="true "></i></button>';
                }
            }
        ],
    });


    // var cardTable = jQuery('#card-table').DataTable({
    //     "pagingType": "numbers",
    //     "responsive": true,
    //     "processing": true,
    //     "serverSide": true,
    //     "orderable": false,
    //     "ajax": {
    //         "url": "{{ route('cards.list') }}",
    //         "dataType": "json",
    //         "type": "POST",
    //         "data": function(data) {
    //             var searchData = $('input[type=search]').value;
    //             var statusData = $('input[type=radio]:checked').val();
    //             data.searchText = searchData;
    //             data.statusData = statusData;
    //             data._token = '{{ csrf_token() }}';
    //         }
    //     },
    //     columns: [{
    //             "data": "card_image",
    //             render: function(data, type, full, meta) {
    //                 return '<img src="' + full.card_image +
    //                     '",width=60px, height=60px />';
    //             }
    //         },
    //         {
    //             "data": "show_id"
    //         },

    //         {
    //             data: "id",
    //             "render": function(data, type, full, meta) {
    //                 return '<button type="submit" onClick="activateOrDeactivate(3,' + data +
    //                     ',2)"><i class="fa fa-trash mr-2 " aria-hidden="true "></i></button>';
    //             }
    //         }
    //     ],
    // });
</script>
<script>
    function checkRequestType(type) {
        var flag;
        switch (type) {
            case 0:
                flag = "{{ route('ethnicities.delete') }}";
                break;
            case 1:
                flag = "{{ route('preferences.delete') }}";
                break;
           
        }
        return flag;
    }

    function checkTableName(type) {
        var flag;
        switch (type) {
            case 0:
                flag = ethnicityTable;
                break;
            case 1:
                flag = preferenceTable;
                break;
            case 2:
                flag = showTable;
                break;
            case 3:
                flag = cardTable;
                break;
        }
        return flag;
    }

    function activateOrDeactivate(type, id, action) {
        var actionUrl = checkRequestType(type);
        var tableName = checkTableName(type);
        Swal.fire({
            title: 'Are you sure you want to delete?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "post",
                    url: actionUrl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data == 'success') {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Deleted Successfully',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Not Deleted',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                        tableName.ajax.reload(true);
                    }
                });
            }
        })
    }
</script>
