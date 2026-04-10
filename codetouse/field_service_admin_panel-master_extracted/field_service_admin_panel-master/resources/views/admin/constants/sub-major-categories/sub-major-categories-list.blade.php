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
            <div class="row">
                <div class="col-md-3">
                    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card card-plain">
                                        <div class="card-header pb-0 text-left">
                                            <h5 class="">Add New</h5>
                                        </div>
                                        <div class="card-body">
                                            {!! Form::open([
                                                'route' => 'sub_major_categories.add',
                                                'files' => true,
                                            ]) !!}
                                            <div>

                                                {!! Form::label('major_category_id', 'Major Category:', ['class' => 'control-label']) !!}
                                                {!! Form::select('major_category_id', $majorCategories->pluck('name_en', 'id'), null, ['class' => 'form-select']) !!}

                                            </div>
                                            <div class="input-group input-group-outline my-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name_en" class="form-control"
                                                    onfocus="focused(this)" onfocusout="defocused(this)" required>
                                            </div>

                                            


                                            <div class="container">
                                                <div class="row">
                                                    <hr />

                                                    <div class="col-md-12 col-md-offset-12">
                                                        <div class="form-group">
                                                            <div class="main-img-preview">
                                                                <img class="thumbnail img-preview"
                                                                    src="<?php echo url('/') . '/images/no_image.png'; ?>">
                                                            </div>
                                                            <div class="input-group">
                                                                <div class="input-group-btn">
                                                                    <br />
                                                                    <div class="fileUpload btn  fake-shadow">
                                                                        <span><i class="glyphicon glyphicon-upload"></i>
                                                                            Choose
                                                                            Image</span>
                                                                        <input id="image" name="image" type="file"
                                                                            class="attachment_upload" required
                                                                            accept="image/*">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <br />
                                            {!! Form::submit('Add', ['class' => 'btn btn-primary col-md-12 col-md-offset-12']) !!}

                                            {!! Form::close() !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                 
                    <div class="modal fade" id="modal-form-update" tabindex="-1" role="dialog"
                        aria-labelledby="modal-form-update" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card card-plain">
                                        <div class="card-header pb-0 text-left">
                                            <h5 class="">Update</h5>
                                        </div>
                                        <div class="card-body">
                                            {!! Form::open([
                                                'route' => 'sub_major_categories.update',
                                                'files' => true,
                                            ]) !!}



                                            <div>

                                                {!! Form::label('u_major_category_id', 'Major Category:', ['class' => 'control-label']) !!}
                                                {!! Form::select('u_major_category_id', $majorCategories->pluck('name_en', 'id'), null, ['class' => 'form-select']) !!}

                                            </div>
                                            <input hidden readonly name="sub_major_category"  id="sub_major_category" value="9" type="text"/>

                                            <div class="input-group input-group-outline my-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="u_name_en" id="u_name_en" value=""
                                                    class="form-control" onfocus="focused(this)"
                                                    onfocusout="defocused(this)" required>
                                            </div>

                                            

                                            <div class="container">
                                                <div class="row">
                                                    <hr />

                                                    <div class="col-md-12 col-md-offset-12">
                                                        <div class="form-group">
                                                            <div class="main-img-preview">
                                                                <img class="thumbnail u-img-preview" id="no_u_image"
                                                                    src="<?php echo url('/') . '/images/no_image.png'; ?>">
                                                            </div>
                                                            <div class="input-group">
                                                                <div class="input-group-btn">
                                                                    <br />
                                                                    <div class="u_fileUpload btn  fake-shadow">
                                                                        <span><i class="glyphicon glyphicon-upload"></i>
                                                                            Choose
                                                                            Image</span>
                                                                        <input id="u_image" name="u_image"
                                                                            type="file" class="attachment_upload"
                                                                            accept="image/*">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                         
                                            <br />

                                            {!! Form::submit('Update', ['class' => 'btn btn-primary col-md-12 col-md-offset-12']) !!}

                                            {!! Form::close() !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">

                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                             <div class="card-header">
                               
                                <div class="row" style="display: flex;justify-content: space-between;">
                                    <div class="col-sm-4"><h5 class="mb-0">Sub Major Categories List </h5>
                                    </div>
                                    <div class="col-sm-4"><button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal"
                                        data-bs-target="#modal-form"  >Add New</button> </div>
                               
                                  </div>
                      
                            <div class="table-responsive">
                                <table class="table table-flush datatable_search">
                                    <thead class="thead-light">

                                        <tr>
                                            <th class="text-center" style="width: 100px;">ID</th>
                                            <th class="d-none d-sm-table-cell" style="width: 20%;"">Name</th>
                                            <th class="d-none d-sm-table-cell" style="width: 20%;"">Major Category</th>
                                            
                                             <th class="d-none d-sm-table-cell" style="width: 20%;">Image</th>
                                            <th>Created By</th>
                                          
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
            ajax: "{{ route('sub_major_categories.anyData') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name_en',
                    name: 'name_en'
                },
                {
                    data: 'major_category_name',
                    name: 'major_category_name'
                },
                 
                {
                    data: 'image',
                    name: 'image',
                    render: function(data) {
                        return "<img src=\"" + data + "\" height=\"50\"/>";
                    }
                }, // {data: 'status', name: 'status'},
                {
                    data: 'created_by',
                    name: 'created_by'
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
        $(document).ready(function() {
             var brand = document.getElementById('image');
            var url = "<?php echo url('/'); ?>";

            var subMajorCategories = <?php echo json_encode($subMajorCategories); ?>;

            if (subMajorCategories.length > 0) {

                document.getElementById("u_name_en").value = subMajorCategories[0].name_en;
                document.getElementById("sub_major_category").value = subMajorCategories[0].id;
 
                document.getElementById("no_u_image").src = url + "/" +
                    subMajorCategories[0].image;
            }


          
             function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('.img-preview').attr('src', e.target.result);
                        $('.u-img-preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#image").change(function() {
                readURL(this);
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('.u-img-preview').attr('src', e.target.result);
                        $('.img-preview').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#u_image").change(function() {
                readURL(this);
            });
        });
        function setSelectedValue(dropDownList, valueToSet) {
    var option = dropDownList.firstChild;
    for (var i = 0; i < dropDownList.length; i++) {
        if (option.value == valueToSet ) {
            option.selected = true;
            return;
        }
        option = option.nextElementSibling;
    }
}
        function setUpdateItem(id)
{

    var subMajorCategories = <?php echo json_encode($subMajorCategories); ?>;
    var majorCategories = <?php echo json_encode($majorCategories); ?>;
    
    var index=-1;
    var i=-1;

    subMajorCategories.forEach(element => {
        i+=1;;
        if(element?.id==id)
        {
            index=i;
        }
    });
    
if (index> -1) {
    document.getElementById("u_major_category_id").selectedIndex = majorCategories.findIndex(y => y.id == subMajorCategories[index].major_category_id);
    document.getElementById("u_name_en").value = subMajorCategories[index].name_en;
    document.getElementById("sub_major_category").value = subMajorCategories[index].id;
    document.getElementById("no_u_image").src = url + "/" +
    subMajorCategories[index].image;
}
}

    </script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ URL::asset('assets/js/material-dashboard.min.js?v=3.0.6') }}"></script>

@endsection
