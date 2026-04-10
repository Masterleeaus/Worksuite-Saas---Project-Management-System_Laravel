<head>
    <link href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css" rel="stylesheet">

    <script src="//code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap4.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
    <link rel="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.bootstrap4.min.css" />
    <link id="pagestyle" href="{{ URL::asset('assets/css/material-dashboard.css?v=3.0.6') }}" rel="stylesheet" />
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/pagination/input.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10,b-1.1.0,b-html5-1.1.0,se-1.1.0/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="css/generator-base.css">
    <link rel="stylesheet" type="text/css" href="css/editor.dataTables.min.css">

    <style>
        div.dataTables_length select {
            border: 1px solid black;
            max-width: 40px;
            min-width: 80px;

            cursor: pointer
        }

        div.dataTables_length select:focus {
            border: 1px solid black;
            max-width: 40px;
            min-width: 80px;

            cursor: pointer
        }


        div.dataTables_filter input {
            border: 1px solid black;
        }

        .dataTables_paginate input {
            width: 40px;
        }

        div.dataTables_filter input:focus {
            border: 1px solid black;
        }

        table.dataTable tbody tr.selected {
            color: white;
            background-color: #52d9b3;
        }

        .card {

            padding-top: 20px;
            padding-right: 20px;
            padding-bottom: 20px;
            padding-left: 20px;
        }

        @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700,300);

        body {
            font: 12px 'Open Sans';
        }

        .form-control,
        .thumbnail {
            border-radius: 2px;
        }

        .btn-danger {
            background-color: #B73333;
        }

        /* File Upload */
        .fake-shadow {
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .fileUpload {
            position: relative;
            overflow: hidden;
        }

        .fileUpload #image {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            font-size: 33px;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
        }

        .u_fileUpload #u_image {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            font-size: 33px;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
        }

        .img-preview {
            max-width: 100%;
        }

        .u-img-preview {
            max-width: 100%;
        }
    </style>
</head>
@extends('admin.layouts.admin-dash-layout')
@section('title', 'Dashboard')
@section('content')

    <body class="g-sidenav-show  bg-gray-200">

        <div class="container-fluid">
             <div class="row mt-4">

                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                             <div class="card-header">
                               
                                <div class="row" style="display: flex;justify-content: space-between;">
                                    <div class="col-sm-4"><h5 class="mb-0"> Customers List </h5>
                                    </div>
                                    <div class="col-sm-4"><a type="button" class="btn btn-outline-primary mb-3" href="{{route("customers.create")}}" >Add New  Customer</a> </div>
                               
                                  </div>
                      
                            <div class="table-responsive">
                                <table class="table table-flush datatable_search">
                                    <thead class="thead-light">

                                        <tr>
                                            <th class="d-none d-sm-table-cell" style="width: 20%;">Account Name</th>
                                            <th class="d-none d-sm-table-cell" style="width: 20%;"">Contact By Name</th>
                                            <th class="d-none d-sm-table-cell" style="width: 20%;"">Contact By Mobile</th>
                                            
                                             
                                            <th>Created At</th>
                                          
                                            <th>Action</th>




                                        </tr>
                                    </thead>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>



            </div>




    </body>


    {{-- <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script> --}}

    <script>
        $('.datatable_search').DataTable({
            responsive: true,


            order: [
                [0, 'desc']
            ],
            dom: 'Blfrtip',
            "lengthMenu": [
                [10, 25, 50, 100, 250, 500, -1],
                [10, 25, 50, 100, 250, 500, 'All']
            ],
            ajax: "{{ route('customers.anyData') }}",
            columns: [ 
                {
                    data: 'account_name',
                    name: 'account_name'
                },
                {
                    data: 'contacted_by_name',
                    name: 'contacted_by_name'
                },
                 
                {
                    data: 'contacted_by_phone',
                    name: 'contacted_by_phone'
                },
                 {
                    data: 'created_at',
                    name: 'created_at'
                },
                

                {
                    data: 'action',
                    name: 'action'
                },
                // {data: 'action', name: 'action', orderable: false, searchable: false}



            ],

            // select: true,

            pagingType: 'input',
            pageLength: 10,
            language: {
                oPaginate: {
                    sNext: '<i class="fa fa-forward"></i>',
                    sPrevious: '<i class="fa fa-backward"></i>',
                    sFirst: '<i class="fa fa-step-backward"></i>',
                    sLast: '<i class="fa fa-step-forward"></i>'
                }
            }


        });
        
  
    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ URL::asset('assets/js/material-dashboard.min.js?v=3.0.6') }}"></script>

@endsection
