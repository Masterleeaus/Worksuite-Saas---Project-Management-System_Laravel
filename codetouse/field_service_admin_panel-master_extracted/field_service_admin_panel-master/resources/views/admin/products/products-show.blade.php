@extends('admin.layouts.admin-dash-layout')
@section('title', 'Dashboard')
 
@include("extras.data-table-js-import")
<head>
    <style>
        .theme-color{
            color: #1EAC9E;
        }
        .w-20 { 
      -webkit-box-flex: 0;
          -ms-flex: 0 0 19.5% !important;
              flex: 0 0 19.5% !important;
     } 
     img {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
    </style>
   
     </head>
</head>
@section('content')
    <div class="container-fluid py-4">
         <div class="row">
            <div class="col-12">
                <div class="card" >
                    <div class="card-body">
                        <h5 class="mb-2"><b>Product Details</b></h5>
                        <hr>
                        <div class="row">
                            <div class="col-xl-5 col-lg-5 col-md-12  justify-content-between" style="margin:10px 0px 0px 0px; ">
                                <div >
                                    <div  class="card" style="height:100%;width:100%;" >
                                        
                                        <script>
                                       
                                            var product = <?php echo json_encode($product); ?>;
                                            var i=0;
                                            var height =$(window).height()/1.5;
                                            document.write('<center><img style="object-fit: contain;padding:10%;max-height:500px;max-width:100%; "      src={{ asset(`/`)}}'+product.image+' alt=""></center>'); 
                                            </script>
                                          
                                    </div>
                                   
                
                                   </div>
                                   
                                 
                             </div>
                             <div class="col-lg-7 col-xl-7 col-md-12 mx-auto card d-flex justify-content-end" style="padding:20px;margin:10px 0px 0px 0px">
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h5 class=""> <b style="color:#1EAC9E;">General Information</b></h5></div>
                                    <div class="d-flex justify-content-start">  <h5 class="">ID :<b class="theme-color"> {!! $product->id !!}</b></h5></div>
                            </div>
                            

                                <hr>


                       <br/>

                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h5 class="">Name:   &nbsp; </h5></div>
                                    <div class="d-flex justify-content-end">  <h5  style="color:#1EAC9E;"> <b> {!! $product->name!!}</b></h5></div>
                                </div>
                             
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Parent Brand : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! $product->parentBrand->name_en !!}</b></h6></div>
                                </div>
                             
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Brand : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! $product->brand->name_en !!}</b></h6></div>
                                </div>
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Uom Type : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! $product->uomType->name_en !!}</b></h6></div>
                                </div>
                                
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Uom : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! $product->uom->name_en !!}</b></h6></div>
                                </div>
                                
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Color : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! $product->color !!}</b></h6></div>
                                </div>
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Status : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! $product->status->name !!}</b></h6></div>
                                </div>
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Stock qty : </h6></div>
                                    <div class="d-flex justify-content-center">   <h6> {!! $product->stock!!}</b></h6></div>
                                </div>
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">SKU : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! $product->sku!!}</b></h6></div>
                                </div>
                                <div class="mt-lg-0 mt-2">
                                   <u> <h6  >Description:</h6></u>

                                     {!! $product->description !!} 

                                </div>
                                <br/>
                                
                                

                                

                                
                             

                                
                        
                  

            

                              
               
                         

                              

                               



                         


                             
                               
                       
                    </div>
                </div>
              
                
            </div>
            <div class="d-flex justify-content-between" style="padding: 10px 10px 30px 10px;margin: 10px 10px 10px 10px;"> 
            <div class="col-lg-5 col-xl-5 col-md-5 mx-auto card d-flex justify-content-between" style="padding:20px;margin:10px 0px 0px 0px">
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h5 class=""> <b style="color:#1EAC9E;">Pricing</b></h5></div>
                                    <div class="d-flex justify-content-start">  <h5 class=""> <b class="theme-color"></b></h5></div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Cost Price : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! number_format($product->cost_price,3) !!} OMR</b></h6></div>
                            </div> 
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Product Price : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! number_format($product->purchase_price,3) !!} OMR</b></h6></div>
                            </div>  
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">WSP : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! number_format($product->whole_sale_price,3) !!} OMR</b></h6></div>
                            </div> 
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">MWSP : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! number_format($product->maximum_whole_sale_price,3) !!} OMR</b></h6></div>
                            </div> 
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">MSRP : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!! number_format($product->product_msrp,3) !!} OMR</b></h6></div>
                            </div> 
                            
            </div>              
             <div class="col-lg-7 col-xl-7 col-md-7 mx-auto card d-flex justify-content-end" style="padding:20px;margin:10px 0px 0px 0px">
                                <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h5 class=""> <b style="color:#1EAC9E;">Classification</b></h5></div>
                                    <div class="d-flex justify-content-start">  <h5 class=""> <b class="theme-color"> </b></h5></div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Major Category : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!!  $product->majorCategory->name_en !!} </b></h6></div>
                            </div> 
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Sub Major Category : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!!  $product->subMajorCategory->name_en !!} </b></h6></div>
                            </div> 
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">Sub Category : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!!  $product->subCategory->name_en !!} </b></h6></div>
                            </div> 
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">ERP Code : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!!  $product->erp_code !!} </b></h6></div>
                            </div> 
                            <div class="d-flex justify-content-between" style="width:100%;" >
                                    <div class="d-flex justify-content-start">  <h6 class="">SFA Code : </h6></div>
                                    <div class="d-flex justify-content-start">   <h6> {!!  $product->sfa_code!!} </b></h6></div>
                            </div> 
            </div>
            </div>

                      
       
        
    </div>
  
 
@endsection
