<html>
    <head>
<link href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="//cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css" rel="stylesheet">

{{-- <link id="pagestyle" href="{{ URL::asset('assets/css/material-dashboard.css?v=3.0.6') }}" rel="stylesheet" /> --}}
<script src="{{ URL::asset('assets/js/material-dashboard.min.js?v=3.0.6') }}"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


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
{{-- <link id="pagestyle" href="{{ URL::asset('assets/css/material-dashboard.css?v=3.0.6') }}" rel="stylesheet" /> --}}
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.15/pagination/input.js"></script>
<link rel="stylesheet" type="text/css"

    href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10,b-1.1.0,b-html5-1.1.0,se-1.1.0/datatables.min.css">
<link rel="stylesheet" type="text/css" href="css/generator-base.css">
<link rel="stylesheet" type="text/css" href="css/editor.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <defs>
    <symbol xmlns="http://www.w3.org/2000/svg" id="link" viewBox="0 0 24 24">
      <path fill="currentColor" d="M12 19a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0-4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm-5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm7-12h-1V2a1 1 0 0 0-2 0v1H8V2a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 17a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V6a1 1 0 0 1 1-1h1v1a1 1 0 0 0 2 0V5h8v1a1 1 0 0 0 2 0V5h1a1 1 0 0 1 1 1ZM7 15a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="arrow-right" viewBox="0 0 24 24">
      <path fill="currentColor" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="category" viewBox="0 0 24 24">
      <path fill="currentColor" d="M19 5.5h-6.28l-.32-1a3 3 0 0 0-2.84-2H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-10a3 3 0 0 0-3-3Zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1h4.56a1 1 0 0 1 .95.68l.54 1.64a1 1 0 0 0 .95.68h7a1 1 0 0 1 1 1Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="calendar" viewBox="0 0 24 24">
      <path fill="currentColor" d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="heart" viewBox="0 0 24 24">
      <path fill="currentColor" d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="plus" viewBox="0 0 24 24">
      <path fill="currentColor" d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="minus" viewBox="0 0 24 24">
      <path fill="currentColor" d="M19 11H5a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24">
      <path fill="currentColor" d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="check" viewBox="0 0 24 24">
      <path fill="currentColor" d="M18.71 7.21a1 1 0 0 0-1.42 0l-7.45 7.46l-3.13-3.14A1 1 0 1 0 5.29 13l3.84 3.84a1 1 0 0 0 1.42 0l8.16-8.16a1 1 0 0 0 0-1.47Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="trash" viewBox="0 0 24 24">
      <path fill="currentColor" d="M10 18a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1ZM20 6h-4V5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2ZM10 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm7 14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8h10Zm-3-1a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="star-outline" viewBox="0 0 15 15">
      <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.804L5.337 11l.413-2.533L4 6.674l2.418-.37L7.5 4l1.082 2.304l2.418.37l-1.75 1.793L9.663 11L7.5 9.804Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="star-solid" viewBox="0 0 15 15">
      <path fill="currentColor" d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="search" viewBox="0 0 24 24">
      <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24">
      <path fill="currentColor" d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 15 15">
      <path fill="currentColor" d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z"/>
    </symbol>
  </defs>
</svg>
 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
      
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
  
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous"> --}}

{{-- <link rel="stylesheet" type="text/css" href="css/vendor.css"> --}}
{{-- <link rel="stylesheet" type="text/css" href="style.css"> --}}

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
<title>Tamween </title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
   <link rel="stylesheet" href={{ asset( "plugins/fontawesome-free/css/all.min.css") }}>
   <link rel="stylesheet" href={{ asset("dist/css/adminlte.min.css") }}>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href={{ asset( "plugins/fontawesome-free/css/all.min.css") }}>
<link rel="stylesheet" href={{ asset("dist/css/adminlte.min.css") }}>
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
 <link rel="stylesheet" href={{ asset( "plugins/fontawesome-free/css/all.min.css") }}>
 {{-- <link rel="stylesheet" href={{ asset("dist/css/adminlte.min.css") }}> --}}
 <style>
   #page-container {
 position: relative;
 min-height: 100vh;
}
ul#menu li {
   display:inline;
 }
 .hover:hover {
 background-color: #bcf3ee;
}
hr {
border: 0;
clear:both;
display:block;
width: 96%;               
background-color:#FFFF00;
height: 1px;
}
#content-wrap {
 padding-bottom: 0.5rem;    /* Footer height */
}

#footer {
 position: absolute;
 bottom: 0;
 width: 100%;
 height: 2.5rem;            /* Footer height */
}
ul#menu li {
   display:inline;
 }
 .hover:hover {
 background-color: #bcf3ee;
}
.center {
 align-items:center;
 justify-content:center;
 display: flex;

}
.btn .btn-primary{
  border-color:#1EAC9E
}
html {
 overflow:   scroll;
}

::-webkit-scrollbar {
   width: 0px;
   background: transparent; /* make scrollbar transparent */
}
       .slider {
         width: 500px;
         height: 300px;
         background-color: yellow;
         margin-left: auto;
         margin-right: auto;
         margin-top: 0px;
         text-align: center;
         overflow: hidden;
       }
       .image-container {
         width: 1500px;
         background-color: pink;
         height: 300px;
         clear: both;
         position: relative;
         -webkit-transition: left 2s;
         -moz-transition: left 2s;
         -o-transition: left 2s;
         transition: left 2s;
       }
       .slide {
         float: left;
         margin: 0px;
         padding: 0px;
         position: relative;
       }
       #slide-1:target ~ .image-container {
         left: 0px;
       }
       #slide-2:target ~ .image-container {
         left: -500px;
       }
       #slide-3:target ~ .image-container {
         left: -1000px;
       }
       .buttons {
         position: relative;
         top: -20px;
       }
       .buttons a {
         display: inline-block;
         height: 15px;
         width: 15px;
         border-radius: 50px;
         background-color: lightgreen;
       }
       hr {
 margin:0;
 padding: 0;
 color: inherit;
 background-color: currentColor;
 border: 0;
 opacity: 0.25;
}
     </style>
</head>
<script type="text/javascript">
function checkAuth()
{
  var isLoggedIn =  {{ auth()->check() ? 'true' : 'false' }};
  // alert(isLoggedIn);
  if(!isLoggedIn)
  window.location.href = "{{ url('login')}}";


}

function markFavorite(id){
  checkAuth();

$.ajax({
    url : '{!! url("/customer/favorites/add-remove?product_id='+id+'") !!}',
    data : [],
    success : function(data){
      // alert(data);
      loadProductsData();
 
     }
}); }
function addRow(item)
   {var htmlRow=``;

 
    htmlRow+=`<div class="col">
                      <div href="" class="product-item">`;
                        // if(loggedIn)
                        // {
                          htmlRow+=`<a    onclick="markFavorite(`+item.id+`)"  id="1" ><span   class="hover material-symbols-outlined  icon-style">`+(item?.favorite?.id>0?'pages':'star')+`</span></a>`;
                        // }
                     htmlRow+=`<figure>
                          <a href="{{url('customer/products/show/`+item.id+`')}}" title="">
                            <img style="object-fit: contain;height:20vh;  width : 20vw;"  src="{{asset('`+item?.image_1+`')}}"  class="">
                          </a>
                        </figure>
                        <div class="d-flex justify-content-between" style="width:100%;" >
                          <div style="width:80%;height:45px;" class="d-flex justify-content-start">    <h3>`+item?.name_en+`</h3></div>
                          <div  class="d-flex justify-content-end">   <b >`+(item?.sales>0?(item?.sales+` %`):``)+`&nbsp;</b></div>
                        </div>
                      <span class="qty"> Unit : `+item?.unit?.name_en+` </span>
                        <p>`+item?.description_en+`</p>
                        <div class="d-flex align-items-center justify-content-between">
                         <div class="col">
                         <div> <span class="text"><s><i>`+(item?.sales>0?(item?.price+item?.vat+item?.affiliation_amount).toFixed(3)+'  ':"<br/>")+` </i></s></span></div>
                          <span class="text">`+(((item?.price/100)*(100-item?.sales??0))+item?.vat+item?.affiliation_amount).toFixed(3)+` OMR</span>
                         </div>
                          <div class="input-group product-qty">
                              <span class="input-group-btn">
                                  <button type="button" onClick="updateCart(`+item?.id+`,'minus')" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field="">
                                    <svg width="32" height="32"><use xlink:href="#minus"></use></svg>
                                  </button>
                              </span>
                              <input type="text" id="quantity" name="quantity" class="form-control input-number" readonly value="`+( item?.carts[0]?.qty??0)+`" min="1" max="100">
                              <span class="input-group-btn">
                                  <button type="button" onClick="updateCart(`+item?.id+`,'add')" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
                                      <svg width="32" height="32"><use xlink:href="#plus"></use></svg>
                                  </button>
                              </span>
                          </div>
                         </div>
                      </div>
                    </div>`;
    
return htmlRow;
   }
   </script>
    </html>