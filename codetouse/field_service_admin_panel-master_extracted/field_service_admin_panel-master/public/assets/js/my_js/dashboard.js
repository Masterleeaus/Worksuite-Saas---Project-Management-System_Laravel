function drawCart(items)
{        var qty=0;
 var productsHtml=``;
   // alert(JSON.stringify(items));
   items.forEach(element => {
     qty+=element.qty;
     productsHtml+=drawCartProductItem(element);


   });
    
   document.getElementById("cart_products").innerHTML =productsHtml??"";
   document.getElementById("cart_items").innerHTML =qty??"";
   document.getElementById("inner_cart_items").innerHTML =qty??"";
   
 
}
function drawCartProductItem(item)
{
 // alert(JSON.stringify(item));
return `
 <li class="list-group-item d-flex justify-content-between lh-sm">
             <div class="d-flex justify-content-start lh-sm">

             <img    height="50px" width="50px" src="{{asset('/')}}`+item?.image_1+`" /> 
            
             <div style="padding: 5px;">
               <h6 class="my-0">`+item?.name_en+`</h6>
               <small class="text-body-secondary">Qty: `+item?.qty+`</small>
             </div>
           </div>
             </
             <span class="text-body-secondary">`+(item.price+item.vat+item.affiliation_amount)+`OMR</span>
           </li>
 `;
 }
function update(){
 $.ajax({
       url : '{!! url("/customer/home/load-dashboard") !!}',
       data : [],
       success : function(data){
         drawCart(data?.cart);
         // alert(JSON.stringify(data?.cart));
        }
   });



}


function addRow(item)
  {var htmlRow=``;


   htmlRow+=`<div class="col">
                     <div href="" class="product-item">`;
                       // if(loggedIn)
                       // {
                         htmlRow+=`<a href="#" onclick="markFavorite(`+item.id+`)"  id="1" ><span   class="hover material-symbols-outlined  icon-style">`+(item?.favorite?.id>0?'pages':'star')+`</span></a>`;
                       // }
                       // htmlRow+=`<div><span href="#" onclick="markFavorite(`+item.id+`)" class="material-symbols-outlined">star</span></div>`;
                   htmlRow+=`<figure>
                         <a href="{{url('customer/products/show/`+item.id+`')}}" title="">
                           <img style="object-fit: contain;width:70%;height:40%;"  src="{{asset('`+item?.image_1+`')}}"  class="">
                         </a>
                       </figure>
                       <div class="d-flex justify-content-between" style="width:100%;" >
                         <div style="width:80%;height:45px;" class="d-flex justify-content-start">    <h3>`+item?.name_en+`</h3></div>
                         <div  class="d-flex justify-content-end">   <b >`+item?.sales+`%&nbsp;</b></div>
                       </div>
                    



                       <span class="qty"> Unit : `+item?.unit?.name_en+` </span>
                       <div class="d-flex align-items-center justify-content-between">
                        <div class="col">
                        <div> <span class="text"><s><i>`+(item?.sales>0?(item?.price+item?.vat+item?.affiliation_amount).toFixed(3)+'  ':"<br/>")+` </i></s></span></div>
                         <span class="text">`+(item?.price+item?.vat+item?.affiliation_amount).toFixed(3)+` OMR</span>
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

  function addCategoryRow(item)
  {var htmlRow=``;


   htmlRow+=`<div class="col">
                     <div href="" class="product-item">`;
                    htmlRow+=`<figure>
                         <a href="{{url('customer/products/show/`+item.id+`')}}" title="">
                           <img style="object-fit: contain;width:70%;height:40%;"  src="{{asset('`+item?.image+`')}}"  class="">
                         </a>
                       </figure>
                       <div class="d-flex justify-content-between" style="width:100%;" >
                         <div style="width:80%;" class="d-flex justify-content-center">    <h6 style="height:45px;"><center>`+item?.name_en+`</center></h6></div>
                         
                       </div>
                       </div>
                   </div>`;
   
return htmlRow;
  }
  