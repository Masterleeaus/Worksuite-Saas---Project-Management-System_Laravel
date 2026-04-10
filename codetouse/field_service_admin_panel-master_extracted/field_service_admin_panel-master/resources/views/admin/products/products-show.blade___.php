@extends('admin.layouts.admin-dash-layout')
@section('title', 'Dashboard')
@section('content')

    <div class="container-fluid py-4">
         <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">Product Details</h5>
                        <div class="row">
                            <div class="col-xl-5 col-lg-6 text-center">
                                <img class="w-100 border-radius-lg shadow-lg mx-auto" src="{{ $baseUrl . $product->image_1 }}"
                                    alt="chair">
                                <div class="my-gallery d-flex mt-4 pt-2" itemscope
                                    itemtype="http://schema.org/ImageGallery">
                                    {{-- <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                        <a href="{{ $baseUrl . $product->image_1 }}"
                                            itemprop="contentUrl" data-size="500x600">
                                            <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow"
                                                src="{{ URL::asset('assets/img/products/product-details-2.jpg') }}"
                                                alt="Image description" />
                                        </a>
                                    </figure>
                                    <figure class="ms-3" itemprop="associatedMedia" itemscope
                                        itemtype="http://schema.org/ImageObject">
                                        <a href="{{ URL::asset('assets/img/products/product-details-3.jpg') }}"
                                            itemprop="contentUrl" data-size="500x600">
                                            <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow"
                                                src="{{ $baseUrl . $product->image_1 }}" itemprop="thumbnail"
                                                alt="Image description" />
                                        </a>
                                    </figure>
                                    <figure class="ms-3" itemprop="associatedMedia" itemscope
                                        itemtype="http://schema.org/ImageObject">
                                        <a href="{{ URL::asset('assets/img/products/product-details-4.jpg') }}"
                                            itemprop="contentUrl" data-size="500x600">
                                            <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow"
                                                src="{{ URL::asset('assets/img/products/product-details-4.jpg') }}"
                                                itemprop="thumbnail" alt="Image description" />
                                        </a>
                                    </figure>
                                    <figure class="ms-3" itemprop="associatedMedia" itemscope
                                        itemtype="http://schema.org/ImageObject">
                                        <a href="{{ URL::asset('assets/img/products/product-details-5.jpg') }}"
                                            itemprop="contentUrl" data-size="500x600">
                                            <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow"
                                                src="{{ URL::asset('assets/img/products/product-details-5.jpg') }}"
                                                itemprop="thumbnail" alt="Image description" />
                                        </a>
                                    </figure> --}}
                                </div>
                                <!-- Root element of PhotoSwipe. Must have class pswp. -->
                                <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                                    <!-- Background of PhotoSwipe.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        It's a separate element, as animating opacity is faster than rgba(). -->
                                    <div class="pswp__bg"></div>
                                    <!-- Slides wrapper with overflow:hidden. -->
                                    <div class="pswp__scroll-wrap">
                                        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
                                        <!-- don't modify these 3 pswp__item elements, data is added later on. -->
                                        <div class="pswp__container">
                                            <div class="pswp__item"></div>
                                            <div class="pswp__item"></div>
                                            <div class="pswp__item"></div>
                                        </div>
                                        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                                        <div class="pswp__ui pswp__ui--hidden">
                                            <div class="pswp__top-bar">
                                                <!--  Controls are self-explanatory. Order can be changed. -->
                                                <div class="pswp__counter"></div>
                                                <button class="btn btn-white btn-sm pswp__button pswp__button--close">Close
                                                    (Esc)</button>
                                                <button
                                                    class="btn btn-white btn-sm pswp__button pswp__button--fs">Fullscreen</button>
                                                <button
                                                    class="btn btn-white btn-sm pswp__button pswp__button--arrow--left">Prev
                                                </button>
                                                <button
                                                    class="btn btn-white btn-sm pswp__button pswp__button--arrow--right">Next
                                                </button>
                                                <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                                                <!-- element will get class pswp__preloader--active when preloader is running -->
                                                <div class="pswp__preloader">
                                                    <div class="pswp__preloader__icn">
                                                        <div class="pswp__preloader__cut">
                                                            <div class="pswp__preloader__donut"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                                <div class="pswp__share-tooltip"></div>
                                            </div>
                                            <div class="pswp__caption">
                                                <div class="pswp__caption__center"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 mx-auto">
                                <h3 class="mt-lg-0 mt-4">{!! $product->name_en . ' - ' . $product->name_ar !!}</h3>
                                {{-- <div class="rating">
                                    <i class="material-icons text-lg">grade</i>
                                    <i class="material-icons text-lg">grade</i>
                                    <i class="material-icons text-lg">grade</i>
                                    <i class="material-icons text-lg">grade</i>
                                    <i class="material-icons text-lg">star_outline</i>
                                </div> --}}
                                <br>
                                {{-- <h6 class="mb-0 mt-3">Price</h6>
                                <div class="row">
                                    <h5>{!! $product->price !!}</h5>
                                    <b>{!! $product->vat . ' +vat' !!}</b>
                                </div> --}}
                                <div class="row mt-4">
                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Price</h6>
                                        {!! number_format($product->price, 3) !!} OMR 

                                    </div>

                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Vat</h6>

                                       {!! number_format($product->vat, 3) !!} OMR 

                                    </div>
                                    {{-- <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Affiliate Amount</h6>

                                        <option value="Choice 1" selected="">{!! $product->affiliation_amount !!}</option>

                                    </div> --}}

                                </div>
                                <div class="row mt-4">

                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Affiliation Type</h6>

                                         {!! $product->affiliation_type=="percentage"?"Percentage":"Fixed Amount" !!} 

                                    </div>
                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Affiliation Value</h6>

                                        <option value="Choice 1" selected="">{!! ($product->affiliation_value) . ($product->affiliation_type=="percentage"?" % ": " OMR ") !!}</option>

                                    </div>

                                </div>

                                <div class="row mt-4">

                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Affiliate Amount</h6>

                                     {!! number_format($product->affiliation_amount, 3) !!} OMR 

                                    </div>
                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Discount</h6>

                                        {!! $product->sales !!} % 

                                    </div>

                                </div>

                               



                                <div class="row mt-4">

                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Selling Price</h6>

                                        {!! number_format(
                                            $product->affiliation_amount +
                                                $product->vat +
                                                $product->price -
                                                (($product->price  ) / 100) * $product->sales,
                                            3,
                                        ) !!} OMR

                                    </div>
                                    {{-- <span class="badge badge-success">{!! $product->stock ? 'In Stock' : 'Out of stock' !!}</span> --}}


                                </div>



                                <br>

                                <div class="row mt-4">

                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Brand Name En</h6>

                                         {!! $product->brand_name_en !!} 

                                    </div>
                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Brand Name Ar</h6>

                                         {!! $product->brand_name_ar !!}  

                                    </div>

                                </div>
                                {{-- <label class="mt-4">Brand Name</label>
                                <ul>
                                    <li>{!! $product->brand_name_en !!}</li>
                                    <li>{!! $product->brand_name_ar !!}</li>

                                </ul> --}}
                                <div class="row mt-4">

                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Description En</h6>

                                         {!! $product->description_en !!}  

                                    </div>
                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Description Ar</h6>

                                 {!! $product->description_ar !!}  

                                    </div>

                                </div>
                                {{-- <label class="mt-4">Description</label>
                                <ul>
                                    <li>{!! $product->description_en !!}</li>
                                    <li>{!! $product->description_ar !!}</li>

                                </ul> --}}
                                <div class="row mt-4">
                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Available Stock</h6>

                                        {{-- <label class="ms-0">Available Stock</label> --}}

                                         {!! $product->stock !!} 

                                    </div>

                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Sold Stock</h6>

                                         0 

                                    </div>

                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Category</h6>

                                      {!! $product->category->name_en??"" !!} 

                                    </div>

                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Sub Category</h6>

                                        {!! $product->subCategory->name_en ??""!!} 

                                    </div>

                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Supplier</h6>

                                         {!! $product->supplier->name !!} 

                                    </div>

                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Status</h6>
                                        <?php if ($product->is_active == 1): ?>
                                        <span id="span" style="color:green"> Active</span>
                                        <?php endif ?>
                                        <?php if ($product->is_active == 0): ?>
                                        <span id="span" style="color:orange"> Pending</span>
                                        <?php endif ?>
                                        <?php if ($product->is_active >1 ): ?>
                                        <span id="span" style="color:red"> Rejected</span>
                                        <?php endif ?>

                                    </div>



                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-5 mt-lg-0 mt-2">
                                        <h6 class="mb-0 mt-3">Made in</h6>

                                      {!! $product->madein !!} 

                                    </div>

                                </div>
                                <?php if ($product->is_active == 1): ?>
                                <div class="row mt-4">
                                    <div class="col-lg-5">

                                        <form
                                            action="{{ url('products/update-status/' . $product->id) . '/reject' . '/all' . '/' . 'token?dljsdhjksh' }}"
                                            method="GET">
                                            <input type="hidden" name="_method" value=" Block ">
                                            <input type="submit" name="submit" value=" Block " class="btn btn-danger"
                                                onClick="return confirm(\'Are you sure you want to block this product?\')"">
                                        </form>
                                    </div>
                                    <div class="col-lg-5">

                                        <form action="{{ url('products/update/' . $product->id) }}" method="GET">
                                            <input type="hidden" name="_method" value=" Block ">
                                            <input type="submit" name="submit" value=" Update "
                                                class="btn btn-primary">
                                        </form>
                                    </div>
                                    {{-- <div class="col-lg-5">
                                        <form
                                            action="{{ url('products/remove/' . $product->id) . '/all' . '/' . 'token?gbnjfhdskfj' }}"
                                            method="GET">
                                            <input type="hidden" name="_method" value=" Delete ">
                                            <input type="submit" name="submit" value=" Delete " class="btn btn-danger"
                                                onClick="return confirm(\'Are you sure you want to delete this product?\')"">

                                        </form>
                                    </div> --}}
                                </div>
                                <?php endif ?>
                                <?php if ($product->is_active == 0): ?>
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                            data-bs-target="#modal-form">
                                            Approve</button>
                                        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog"
                                            aria-labelledby="modal-form" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body p-0">
                                                        <div class="card card-plain">
                                                            <div class="card-header pb-0 text-left">
                                                                <h5 class="">Set Affiliate Amount</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                {!! Form::open([
                                                                    'route' => 'products.approvePlusUpdateAffiliate',
                                                                    'files' => true,
                                                                ]) !!}
                                                                <input type="text" name="id" id="id"
                                                                    hidden value="{{ $product->id }}"
                                                                    class="form-control">
                                                                <div class="input-group input-group-outline my-3">
                                                                    <label class="form-label">Amount (OMR)</label>
                                                                    <input type="text" name="amount" id="amount"
                                                                        class="form-control" onfocus="focused(this)"
                                                                        onfocusout="defocused(this)" required>
                                                                </div>




                                                                <br />
                                                                {!! Form::submit('Approve', ['class' => 'btn btn-primary col-md-12 col-md-offset-12']) !!}

                                                                {!! Form::close() !!}
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">

                                        <form
                                            action="{{ url('products/update-status/' . $product->id) . '/reject' . '/all' . '/' . 'token?dljsdhjksh' }}"
                                            method="GET">
                                            <input type="hidden" name="_method" value=" Reject ">
                                            <input type="submit" name="submit" value=" Reject " class="btn btn-danger"
                                                onClick="return confirm(\'Are you sure you want to reject this product?\')"">

                                        </form>
                                    </div>
                                    <div class="col-lg-5">

                                        <form action="{{ url('products/update/' . $product->id) }}" method="GET">
                                            <input type="hidden" name="_method" value=" Block ">
                                            <input type="submit" name="submit" value=" Update "
                                                class="btn btn-primary">
                                        </form>
                                    </div>

                                </div>
                                <?php endif ?>
                                <?php if ($product->is_active != 0 &&$product->is_active != 1): ?>
                                <div class="row mt-4">

                                    <div class="col-lg-5">

                                        <form
                                            action="{{ url('products/update-status/' . $product->id) . '/approve' . '/all' . '/' . 'token?dljsdhjksh' }}"
                                            method="GET">
                                            <input type="hidden" name="_method" value=" Approve ">
                                            <input type="submit" name="submit" value=" Approve "
                                                class="btn btn-primary"
                                                onClick="return confirm(\'Are you sure you want to approve this product?\')"">

                                        </form>
                                    </div>
                                    <div class="col-lg-5">

                                        <form action="{{ url('products/update/' . $product->id) }}" method="GET">
                                            <input type="hidden" name="_method" value=" Block ">
                                            <input type="submit" name="submit" value=" Update "
                                                class="btn btn-primary">
                                        </form>
                                    </div>
                                    {{-- <div class="col-lg-5">
                                        <form
                                            action="{{ url('products/remove/' . $product->id) . '/all' . '/' . 'token?gbnjfhdskfj' }}"
                                            method="GET">
                                            <input type="hidden" name="_method" value=" Delete ">
                                            <input type="submit" name="submit" value=" Delete " class="btn btn-danger"
                                                onClick="return confirm(\'Are you sure you want to delete this product?\')"">

                                        </form>
                                    </div> --}}
                                </div>
                                <?php endif ?>
                            </div>
                        </div>
                    
                        

                        {{-- <div class="row mt-5">
                            <div class="col-12">
                                <h5 class="ms-3">Other Products</h5>
                                <div class="table table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Product</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Price</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Review</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Availability</th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Id</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <img src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/black-chair.jpg"
                                                                class="avatar avatar-md me-3" alt="table image">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Christopher Knight Home</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-secondary mb-0">$89.53</p>
                                                </td>
                                                <td>
                                                    <div class="rating ms-lg-n4">
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">star_outline</i>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-sm">
                                                    <div class="progress mx-auto">
                                                        <div class="progress-bar bg-gradient-success" role="progressbar"
                                                            style="width: 80%" aria-valuenow="80" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">230019</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <img src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/chair-pink.jpg"
                                                                class="avatar avatar-md me-3" alt="table image">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Bar Height Swivel Barstool</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-secondary mb-0">$99.99</p>
                                                </td>
                                                <td>
                                                    <div class="rating ms-lg-n4">
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-sm">
                                                    <div class="progress mx-auto">
                                                        <div class="progress-bar bg-gradient-success" role="progressbar"
                                                            style="width: 90%" aria-valuenow="90" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">87120</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <img src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/chair-steel.jpg"
                                                                class="avatar avatar-md me-3" alt="table image">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Signature Design by Ashley</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-secondary mb-0">$129.00</p>
                                                </td>
                                                <td>
                                                    <div class="rating ms-lg-n4">
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">star_outline</i>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-sm">
                                                    <div class="progress mx-auto">
                                                        <div class="progress-bar bg-gradient-warning" role="progressbar"
                                                            style="width: 60%" aria-valuenow="60" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">412301</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            <img src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/chair-wood.jpg"
                                                                class="avatar avatar-md me-3" alt="table image">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Modern Square</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-secondary mb-0">$59.99</p>
                                                </td>
                                                <td>
                                                    <div class="rating ms-lg-n4">
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                        <i class="material-icons text-lg">grade</i>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-sm">
                                                    <div class="progress mx-auto">
                                                        <div class="progress-bar bg-gradient-warning" role="progressbar"
                                                            style="width: 40%" aria-valuenow="40" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-sm">001992</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
