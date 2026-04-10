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
<?php $name = [];
$name[] = 'ali'; ?>
@extends('admin.layouts.admin-dash-layout')
@section('title', 'Dashboard')
@section('content')

<div style="margin: 20px;"> 
<div class="card" > 
    <div class="card-body" style="padding: 20px;"> 
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"> Add New Product <small></small></h3>
        </div>

    </div>
    {!! Form::open([
        'route' => 'products.store',
        'files' => true,
    ]) !!}

   

    



    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline">
                <label class="form-label">Name</label>
                <input type="text"  @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" class="form-control" required>

                @error('name')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6">
            <div class="input-group input-group-outline">
                <label class="form-label">Color</label>
                <input type="text"  @error('color') is-invalid @enderror" value="{{ old('color') }}"  name="color" class="form-control" required>

                @error('color')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>

       
    </div>

  
    

             <div class="input-group input-group-outline">
               
                <label   for="description_en">Description</label>
                 <textarea style="border-radius: 10px; 
                 border: 1px solid rgb(204, 204, 204);"  @error('description') is-invalid @enderror" value="{{ old('description') }}" class="col-sm-12" type="text"  rows="4"    id="description_en" name="description_en"   required></textarea>
                @error('description')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
    <br/>
    <div class="row">
    <div class="form-group col-sm-12">
        <div class="form-group">
            <div class="input-group-outline">
                {!! Form::label('major_category', 'Major Category:', ['class' => 'control-label']) !!}
                {!! Form::select('major_category',  $majorCategories->pluck('name_en', 'id'), null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('major_category')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6 removeleft">
            <div class="input-group-outline">
                {!! Form::label('sub_major_category', 'Sub Major Category:', ['class' => 'control-label']) !!}
                {!! Form::select('sub_major_category', [], null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('sub_major_category')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class=" input-group-outline">
                {!! Form::label('sub_category', 'Sub Category:', ['class' => 'control-label']) !!}
                {!! Form::select('sub_category', [], null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('sub_category')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 removeleft">
            <div class="input-group-outline">
                {!! Form::label('parent_brand', 'Parent Brand:', ['class' => 'control-label']) !!}
                {!! Form::select('parent_brand', $parentBrands->pluck('name_en', 'id'), null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('parent_brand')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group-outline">
                {!! Form::label('brand', 'Brand:', ['class' => 'control-label']) !!}
                {!! Form::select('brand', [], null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('brand')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 removeleft">
            <div class="input-group-outline">
                {!! Form::label('uom_type', 'Uom Type:', ['class' => 'control-label']) !!}
                {!! Form::select('uom_type', $uomTypes->pluck('name_en', 'id'), null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('uom_type')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group-outline">
                {!! Form::label('uom', 'Uom:', ['class' => 'control-label']) !!}
                {!! Form::select('uom', $uoms->pluck('name_en', 'id'), null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('uom')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
    </div>


    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline ">
                <label class="form-label">Cost Price (OMR) </label>
                <input type="number" @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}" step="0.001" name="cost_price" class="form-control" required>

                @error('cost_price')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline ">
                <label class="form-label">Purchase Price (OMR)</label>
                <input type="number" @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price') }}" step="0.001" name="purchase_price" class="form-control" required>
                @error('purchase_price')
                    <span class=”invalid-feedback” role=“alert”>
                        <p style="color:red">{!! $message !!}</p>

                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline">
                <label class="form-label">MSRP (OMR)</label>
                <input type="number" @error('msrp') is-invalid @enderror" value="{{ old('msrp') }}" step="0.001" name="msrp" class="form-control" required>

                @error('msrp')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline">
                <label class="form-label">Wholesale Price</label>
                <input type="number" @error('whole_sale_price') is-invalid @enderror" value="{{ old('whole_sale_price') }}" step="0.001" name="whole_sale_price" class="form-control" required>
                @error('whole_sale_price')
                    <span class=”invalid-feedback” role=“alert”>
                        <p style="color:red">{!! $message !!}</p>

                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline ">
                <label class="form-label">Maximum Wholesale Price (OMR)</label>
                <input type="number" @error('maximum_whole_sale_price') is-invalid @enderror" value="{{ old('maximum_whole_sale_price') }}" step="0.001" name="maximum_whole_sale_price" class="form-control" required>

                @error('maximum_whole_sale_price')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline">
                <label class="form-label">Stock</label>
                <input type="number" @error('stock') is-invalid @enderror" value="{{ old('stock') }}" step="1" name="stock" class="form-control" required>
                @error('stock')
                    <span class=”invalid-feedback” role=“alert”>
                        <p style="color:red">{!! $message !!}</p>

                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline">
                <label class="form-label">Erp Code (optional)</label>
                <input type="text" @error('erp_code') is-invalid @enderror" value="{{ old('erp_code') }}" name="erp_code" class="form-control">
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline">
                <label class="form-label">Sfa Code (optional)</label>
                <input type="text" @error('sfa_code') is-invalid @enderror" value="{{ old('sfa_code') }}" name="sfa_code" class="form-control" >
            </div>
        </div>
    </div>
    
    <div class="row">

        
        <div class="form-group col-sm-12">
            <div class="input-group input-group-outline">
                <label class="form-label">SKU (optional)</label>
                <input type="text" @error('sku') is-invalid @enderror" value="{{ old('sku') }}" name="sku" class="form-control">
                
            </div>
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
 
    {!! Form::submit('Add Product', ['class' => 'btn btn-primary col-md-12 col-md-offset-12']) !!}

    {!! Form::close() !!}

    </div>
</div>
</div>
</div>
@endsection
<script type="text/javascript">
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

    function loadDefaultSubCategories(){
     var parent=   $("#sub_major_category").val();
        var list = <?php echo json_encode($subCategories); ?>;
            var select2 = $("#sub_category")[0];
            $("#sub_category").empty();
            i=0;
            list.forEach(function myFunction(item, index) {
                if (parent == item.sub_major_category_id) {
                    select2.options[i] = new Option(item.name_en, item.id);
                    i += 1;
                }
            });
    }
    function loadDefaultSubMajorCategories(){
     var parent=   $("#major_category").val();
        var list = <?php echo json_encode($subMajorCategories); ?>;
            var select2 = $("#sub_major_category")[0];
            $("#sub_major_category").empty();
            i=0;
            list.forEach(function myFunction(item, index) {
                if (parent == item.major_category_id) {
                    select2.options[i] = new Option(item.name_en, item.id);
                    i += 1;
                }
            });
    }

    function loadDefaultBrands(){
     var parent=   $("#parent_brand").val();
        var list = <?php echo json_encode($brands); ?>;
            var select2 = $("#brand")[0];
            $("#brand").empty();
            i=0;
            list.forEach(function myFunction(item, index) {
                if (parent == item.parent_brand_id) {
                    select2.options[i] = new Option(item.name_en, item.id);
                    i += 1;
                }
            });
    }
    function loadDefaultUoms(){
     var parent=   $("#uom_type").val();
        var list = <?php echo json_encode($uoms); ?>;
            var select2 = $("#uom")[0];
            $("#uom").empty();
            i=0;
            list.forEach(function myFunction(item, index) {
                if (parent == item.uom_type_id) {
                    select2.options[i] = new Option(item.name_en, item.id);
                    i += 1;
                }
            });
    }
    
    $(document).on('ready', function() {
        loadDefaultSubMajorCategories();
            loadDefaultSubCategories();
            loadDefaultBrands();
            // loadDefaultUoms();
        $("#major_category").on('change', function() {
            loadDefaultSubMajorCategories();
            loadDefaultSubCategories();
        });
        $("#sub_major_category").on('change', function() {
            loadDefaultSubCategories();
        });
        $("#parent_brand").on('change', function() {
            loadDefaultBrands();
        });
        $("#uom_type").on('change', function() {
            // loadDefaultUoms();
            
        });

    });
</script>
