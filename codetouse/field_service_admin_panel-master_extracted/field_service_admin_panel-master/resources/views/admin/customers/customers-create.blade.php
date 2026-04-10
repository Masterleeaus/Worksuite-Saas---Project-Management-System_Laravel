{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
            <h3 class="block-title"> Add New  Customer <small></small></h3>
        </div>
        <hr/>

    </div>
    {!! Form::open([
        'route' => 'customers.store',
        'files' => true,
    ]) !!}







    <div class="row">

        <div class="form-group col-sm-6 removeleft my-3">
            <div class="input-group input-group-outline">
                <label class="form-label">Account Name</label>
                <input type="text" @error('account_name') is-invalid @enderror" value="{{ old('account_name')??"" }}" name="account_name" class="form-control" required>

               
            </div>
            @error('account_name')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
        <div class="form-group col-sm-6 removeright my-3">
            <div class="input-group input-group-outline">
                <label class="form-label">Customer Name</label>
                <input type="text" @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" name="customer_name" class="form-control" required>

               
            </div>
            @error('customer_name')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
         
    </div>

    <div class="row">

        <div class="form-group col-sm-6 removeleft">

            <div class="input-group input-group-outline my-3">
                <label class="form-label"> Official Email</label>
                <input type="email" name="official_email"  @error('official_email') is-invalid @enderror" value="{{ old('official_email') }}" class="form-control" required>
               
            </div>
            @error('official_email')
            <span class=”invalid-feedback” role=“alert”>
                <p style="color:red">{!! $message !!}</p>

            </span>
        @enderror
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Official Phone</label>
                <input type="text" name="official_phone" @error('official_phone') is-invalid @enderror" value="{{ old('official_phone') }}" class="form-control" required>

               
            </div>
            @error('official_phone')
            <span class=”invalid-feedback” role=“alert”>
                <p style="color:red">{!! $message !!}</p>

            </span>
        @enderror
        </div>
    </div>
    


    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline">
                <label class="form-label">Official Fax (optional)</label>
                <input type="text" @error('official_fax') is-invalid @enderror" value="{{ old('official_fax')??"" }}" name="official_fax" class="form-control" >

               
            </div>
            @error('official_fax')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline">
                <label class="form-label">Official Website (optional)</label>
                <input type="text" @error('official_website') is-invalid @enderror" value="{{ old('official_website') }}" name="official_website" class="form-control" >

               
            </div>
            @error('official_website')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
         
    </div>
    <div class="row">

        <div class="form-group col-sm-6 removeleft my-3">
            <div class="input-group input-group-outline">
                <label class="form-label">ERP Code(optional)</label>
                <input type="text" @error('erp_code') is-invalid @enderror" value="{{ old('erp_code')??"" }}" name="erp_code" class="form-control" >

               
            </div>
            @error('erp_code')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
        <div class="form-group col-sm-6 removeright my-3">
            <div class="input-group input-group-outline">
                <label class="form-label">SFA Code (optional)</label>
                <input type="text" @error('sfa_code') is-invalid @enderror" value="{{ old('sfa_code') }}" name="sfa_code" class="form-control" >

               
            </div>
            @error('sfa_code')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
         
    </div>

    <div class="row">

        <div class="form-group col-sm-6 removeleft my-3">
            <div class="input-group input-group-outline">
                <label class="form-label">Latitude</label>
                <input type="text" @error('latitude') is-invalid @enderror" value="{{ old('latitude')??"" }}" name="latitude" class="form-control" >

               
            </div>
            @error('latitude')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
        <div class="form-group col-sm-6 removeright my-3">
            <div class="input-group input-group-outline">
                <label class="form-label">Longitude</label>
                <input type="text" @error('longitude') is-invalid @enderror" value="{{ old('longitude') }}" name="longitude" class="form-control" >

               
            </div>
            @error('longitude')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
         
    </div>
    <div class="row">

        <div class="form-group col-sm-6 removeleft my-3">
            <div class="input-group input-group-outline">
                <label class="form-label">Credit Limit</label>
                <input step="0.001" type="number" @error('credit_limit') is-invalid @enderror" value="{{ old('credit_limit')??"" }}" name="credit_limit" class="form-control" >

               
            </div>
            @error('credit_limit')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
        <div class="form-group col-sm-6 removeright my-3">
            <div class="input-group input-group-outline">
                <label class="form-label">Credit Days</label>
                <input step="1" type="number" @error('credit_days') is-invalid @enderror" value="{{ old('credit_days') }}" name="credit_days" class="form-control" >

               
            </div>
            @error('credit_days')
            <p style="color:red">{!! $message !!}</p>
        @enderror
        </div>
         
    </div>
    <div class="row"> 
    <div class="form-group col-sm-12">
        <div class="form-group">
            <div class="input-group-outline">
                {!! Form::label('payment_type', 'Payment Type:', ['class' => 'control-label']) !!}
                {!! Form::select('payment_type',  ['cash',"credit"], null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('payment_type')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6 removeleft">
            <div class="input-group-outline">
                {!! Form::label('contact_by_id', 'Contacted By:', ['class' => 'control-label']) !!}
                {!! Form::select('contact_by_id', $contacts->pluck('name', 'id'), null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('contact_by_id')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class=" input-group-outline">
                {!! Form::label('supervisor_id', 'Supervisor:', ['class' => 'control-label']) !!}
                {!! Form::select('supervisor_id',  $supervisors->pluck('name', 'id') , null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('supervisor_id')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">

        <div class="form-group col-sm-12 my-3">
            <label class="form-label">Address</label>
            <div class="input-group input-group-outline">
               
                <textarea   rows="4" type="text" @error('address') is-invalid @enderror" value="{{ old('address')??"" }}" name="address" class="form-control" ></textarea>

               
            </div>
            @error('address')
            <p style="color:red">{!! $message !!}</p>
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


    {!! Form::submit('Add Customer', ['class' => 'btn btn-primary col-md-12 col-md-offset-12']) !!}

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
