<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
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

    .invalid-feedback {
        background-color: #B73333
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

    .img-preview {
        max-width: 100%;
    }
</style>
@extends('admin.layouts.admin-dash-layout')
@section('title', 'Dashboard')
@section('content')
<div style="margin: 30px;"> 

<div class="card" >
    <div class="card-body">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"> Add New Super Admin <small></small></h3>
        </div>

    </div>
    {!! Form::open([
        'route' => 'sub_admins.store',
        'files' => true,
    ]) !!}







    <div class="row">

        <div class="form-group col-sm-12 my-3">
            <div class="input-group input-group-outline">
                <label class="form-label">Name</label>
                <input type="text" @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" class="form-control" required>

                @error('name')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
         
    </div>

    <div class="row">

        <div class="form-group col-sm-6 removeleft">

            <div class="input-group input-group-outline my-3">
                <label class="form-label">Email</label>
                <input type="email" name="email"  @error('email') is-invalid @enderror" value="{{ old('email') }}" class="form-control" required>
               
            </div>
            @error('email')
            <span class=”invalid-feedback” role=“alert”>
                <p style="color:red">{!! $message !!}</p>

            </span>
        @enderror
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" @error('contact') is-invalid @enderror" value="{{ old('contact') }}" class="form-control" required>

               
            </div>
            @error('contact')
            <span class=”invalid-feedback” role=“alert”>
                <p style="color:red">{!! $message !!}</p>

            </span>
        @enderror
        </div>
    </div>
    
    <div class="row">
    <div class="form-group col-sm-12">
        <div class="input-group input-group-outline my-3">

            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
           
        </div>
        @error('password')
        <span class=”invalid-feedback” role=“alert”>
            <p style="color:red">{!! $message !!}</p>
         </span>
    @enderror
    </div>
    </div>

    
    <div class="container">
        <div class="row">
            <hr />

            <div class="col-md-4 col-md-offset-4">
                <div class="form-group">
                    <div class="main-img-preview">
                        <img class="thumbnail img-preview" src="<?php echo url('/') . '/images/no_image.png'; ?>">
                    </div>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <br />
                            <div class="fileUpload btn  fake-shadow">
                                <span><i class="glyphicon glyphicon-upload"></i> Choose Image</span>
                                <input id="image" name="image" type="file" class="attachment_upload" required
                                    accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @error('image')
            <span class=”invalid-feedback” role=“alert”>
                <p style="color:red">{!! $message !!}</p>

            </span>
        @enderror
    </div>


    {!! Form::submit('Add Super Admin', ['class' => 'btn btn-primary col-md-12 col-md-offset-12']) !!}

    {!! Form::close() !!}

    </div>
</div>
</div>
</div>

@endsection
<script>
    $(document).ready(function() {
        var brand = document.getElementById('image');

        $('.multi-select').select2();

         function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.img-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#image").change(function() {
            readURL(this);
        });
    });
</script>
