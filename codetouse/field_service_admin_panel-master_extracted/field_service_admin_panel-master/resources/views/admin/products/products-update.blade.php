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


    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"> Update Product <small></small></h3>
        </div>

    </div>
    {!! Form::open([
        'route' => 'products.update',
        'files' => true,
    ]) !!}





    <div class="form-group">
        <div class="form-group">
            <div class="input-group input-group-outline my-3">
                {!! Form::label('supplier', 'Supplier:', ['class' => 'control-label']) !!}
                {!! Form::select('supplier', $suppliers->pluck('name_en', 'user_id'), null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('supplier')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
    </div>

    <input value="" hidden type="text" name="id" id="id" class="form-control" required>

    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Name En</label>
                <input value="" type="text" name="name_en" id="name_en" class="form-control" required>

                @error('name_en')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Name Ar</label>
                <input type="text" value="" id="name_ar" name="name_ar" class="form-control" required>
                @error('name_ar')
                    <span class=”invalid-feedback” role=“alert”>
                        <p style="color:red">{!! $message !!}</p>

                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Brand Name En</label>
                <input type="text" value="" name="brand_name_en" id="brand_name_en" class="form-control" required>

                @error('brand_name_en')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Brand Name Ar</label>
                <input type="text" id="brand_name_ar" value="" name="brand_name_ar" class="form-control" required>
                @error('brand_name_ar')
                    <span class=”invalid-feedback” role=“alert”>
                        <p style="color:red">{!! $message !!}</p>

                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline my-3">
                <label   for="description_en">Description En</label>
                 <textarea type="text"  rows="4" cols="70"   id="description_en" name="description_en"   required></textarea>
                @error('description_en')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
                {{-- <label class="form-label">Description En</label>
                <input type="text" style="height:100px" value="" id="description_en" name="description_en"
                    class="form-control" required>

                @error('description_en')
                    <p style="color:red">{!! $message !!}</p>
                @enderror --}}
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline my-3">
                <label for="description_ar">Description Ar</label>
                <textarea type="text" type="text"  rows="4" cols="70"   id="description_ar" name="description_ar"   required></textarea>

                @error('description_ar')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
                {{-- <label class="form-label">Description Ar</label>
                <input id="description_ar" value="" type="text" style="height:100px" name="description_ar"
                    class="form-control" required>

                @error('description_ar')
                    <p style="color:red">{!! $message !!}</p>
                @enderror --}}
            </div>
        </div>
    </div>


    <div class="row">

        <div class="form-group col-sm-6">
            <div class="form-group">
                <div class="input-group input-group-outline my-3">
                    {!! Form::label('categories', 'Categories:', ['class' => 'control-label']) !!}
                    {!! Form::select('categories', $categories->pluck('name_en', 'id'), null, [
                        'class' => 'form-control multi-select',
                    ]) !!}
                    @error('categories')
                        <p style="color:red">{!! $message !!}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline my-3">
                {!! Form::label('sub_categories', 'Sub Categories:', ['class' => 'control-label']) !!}
                {!! Form::select('sub_categories', [], null, [
                    'class' => 'form-control multi-select',
                ]) !!}
                @error('sub_categories')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Sales (%)</label>
                <input id="sales" value="" type="number" step="0.001" name="sales" class="form-control"
                    required>

                @error('sales')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Price (OMR)</label>
                <input id="price" value="" type="number" step="0.001" name="price" class="form-control"
                    required>
                @error('price')
                    <span class=”invalid-feedback” role=“alert”>
                        <p style="color:red">{!! $message !!}</p>

                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Vat %</label>
                <input value="" id="vat" type="number" step="0.001" name="vat" class="form-control"
                    required>

                @error('vat')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group col-sm-6 removeright">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Stock</label>
                <input id="stock" value="" type="number" name="stock" class="form-control" required>
                @error('stock')
                    <span class=”invalid-feedback” role=“alert”>
                        <p style="color:red">{!! $message !!}</p>

                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">

        <div class="form-group">
            <div class="input-group">
                {!! Form::label('unit', 'Unit:', ['class' => 'control-label']) !!}
                {!! Form::select('unit', $units->pluck('name_en', 'id'), null, [
                    'class' => 'multi-select form-control',
                ]) !!}
                @error('unit')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>


    </div>
    <p>Please Select Affiliation Type</p>
    <div class="row">
<div class="form-group col-sm-6 removeleft">
      <input type="radio" id="fixed" name="affiliation_type" value="fixed">
      <label for="fixed">Fixed Amount</label><br>
</div>
<div class="form-group col-sm-6 removeright">
      <input type="radio" id="percentage" name="affiliation_type" value="percentage">
      <label for="css">Percentage</label><br>
</div>
    </div>
    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Affiliation Value</label>
                <input id="affiliation_amount" value="" type="number" step="0.001" name="affiliation_amount"
                    class="form-control" required>

                @error('affiliation_amount')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
        <div class="form-group  col-sm-6">
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Notify Stock</label>
                <input id="notify_stock" value="" type="number" name="notify_stock" class="form-control"
                    required>
                @error('notify_stock')
                    <span class=”invalid-feedback” role=“alert”>
                        <p style="color:red">{!! $message !!}</p>

                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">

        <div class="form-group col-sm-6 removeleft">
            <div class="input-group input-group-outline my-3">
                <label class="control-label">Made In</label>
				  <select name="madein" class="multi-select form-control" id="madein">
					<option value="Oman">Oman</option>
					<option value="Other">Other</option>
				  </select>

                @error('madein')
                    <p style="color:red">{!! $message !!}</p>
                @enderror
            </div>
        </div>
    </div>



    <div class="container">
        <div class="row">
            <hr />

            <div class="col-md-4 col-md-offset-4">
                <div class="form-group">
                    <div class="main-img-preview">
                        <img class="thumbnail img-preview" src="<?php echo url('/') . '/' . $product->image_1; ?>">
                    </div>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <br />
                            <div class="fileUpload btn  fake-shadow">
                                <span><i class="glyphicon glyphicon-upload"></i> Choose Image</span>
                                <input id="image" name="image" type="file" class="attachment_upload"
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

    {!! Form::submit('Update Product', ['class' => 'btn btn-primary col-md-12 col-md-offset-12']) !!}

    {!! Form::close() !!}

    </div>

@endsection
<script type="text/javascript">
    $(document).ready(function() {
        var product = <?php echo json_encode($product); ?>;

        var select = $("#sub_categories")[0];
        var subCats = <?php echo json_encode($subCategories); ?>;
        $("#sub_categories").empty();

        // alert(JSON.stringify(subCats));
        var i = 0;
        subCats.forEach(function myFunction(item, index) {
            if (product.category_id == item.category_id) {
                select.options[i] = new Option(item.name_en, item.id);
                i += 1;
            }
        });
        // alert(JSON.stringify(product));
        document.getElementById('name_en').value = product.name_en;
        document.getElementById('name_ar').value = product.name_ar;
        document.getElementById('brand_name_en').value = product.brand_name_en;
        document.getElementById('brand_name_ar').value = product.brand_name_ar;
        document.getElementById('description_en').value = product.description_en;
        document.getElementById('description_ar').value = product.description_ar;
        document.getElementById('categories').value = product.category_id;
        document.getElementById('sub_categories').value = product.sub_category_id;
        document.getElementById('supplier').value = product.supplier_id;
        document.getElementById('sales').value = product.sales;
        document.getElementById('vat').value = product.vat_percentage;
        document.getElementById('affiliation_amount').value =product.affiliation_value?? product.affiliation_amount;
        document.getElementById('notify_stock').value = product.notify_qty;
        document.getElementById('price').value = product.price;
        document.getElementById('stock').value = product.stock;
        document.getElementById('madein').value = product.madein;
        document.getElementById('id').value = product.id;
        if(product.affiliation_type=="percentage")
        document.getElementById('percentage').checked = true;
        if(product.affiliation_type=="fixed")
        document.getElementById('fixed').checked = true;






        var brand = document.getElementById('image');

        $('.multi-select').select2();

        // Source: http://stackoverflow.com/a/4459419/6396981
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

    $(document).on('ready', function() {

        $("#categories").on('change', function() {
            var el = $(this);
            var select = $("#sub_categories")[0];
            var subCats = <?php echo json_encode($subCategories); ?>;
            $("#sub_categories").empty();

            // alert(JSON.stringify(subCats));
            var i = 0;
            subCats.forEach(function myFunction(item, index) {
                if (el.val() == item.category_id) {
                    select.options[i] = new Option(item.name_en, item.id);
                    i += 1;
                }
            });

        });

    });
</script>
