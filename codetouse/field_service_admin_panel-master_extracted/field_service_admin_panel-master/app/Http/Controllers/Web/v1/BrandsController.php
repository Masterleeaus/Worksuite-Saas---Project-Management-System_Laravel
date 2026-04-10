<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\ParentBrand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandsController extends Controller
{
    public function index()
    {
        $brands=Brand::all();
        $parent_brands=ParentBrand::all();
        
         return view("admin.constants.brands.brands-list")
         ->withBrands($brands)
         ->withParentBrands($parent_brands);

    }
   
    public function any_data()
    {
        return datatables()->of(Brand::select(['*'])->orderBy('id', 'desc'))
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })->addColumn('image', function ($item) {
            return url('/') . "/" . $item->image;
        })->addColumn('created_by', function ($item) {
            return  $item->createdBy->name;
        })->addColumn('parent_brand_name', function ($item) {
            return  $item->parentBrand->name_en;
        })->addColumn('parent_brand_id', function ($item) {
            return  $item->parentBrand->id;
        })->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("brands/remove/" . $item->id) . '>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete Brand?\')"">
            <i class="fa fa-trash-o"></i>
</button>
             </form>' ).'<form  action=>
                 <input type="hidden" name="_method" value="">
                 <button type="submit" style="margin-right:5px;margin-left:5px;" name="submit" value="" class="btn btn-outline-primary" onClick="return">
                 <i class="fa fa-eye"></i>
</button>
                  </form>'.(!Auth()->user()->hasRole("admin")?'':'<form  action=#>
                  <input type="hidden" name="_method" value="">
                  <button id='.$item->id.'" data-bs-target="#modal-form-update" data-bs-toggle="modal" style="margin-right:5px;margin-left:5px;"   value="" class="btn btn-outline-primary" onClick="setUpdateItem('.$item->id.')">
                  <i class="fa fa-edit"></i>
 </button>
                   </form>' ).
                    ' </div>';
        }) ->make(true);

    }

    public function update(Request $request)
    {
        // return $request;

         $request->validate([
            "u_name_en" => "required",
            "brand" => [
                Rule::exists("brands", "id"),
                "required"
            ],
            "u_parent_brand_id" => [
                Rule::exists("parent_brands", "id"),
                "required"
            ],
            "u_image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $brand = Brand::find($request->brand);
        $brand->name_en = $request->u_name_en;
        $brand->parent_brand_id = $request->u_parent_brand_id;
         
          if ($_FILES["u_image"]["size"] > 0) {
            $filename = time() . '.' . request()->u_image->getClientOriginalExtension();
            request()->u_image->move(public_path('images/brands/'), $filename);
            $brand["image"] = 'images/brands/' . $filename;

        }

        $brand->save();
        return redirect('/brands/index');


    }

    public function add(Request $request)
    {

        $request->validate([
            "name_en" => "required",
             "image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
             "parent_brand_id" => [
                Rule::exists("parent_brands", "id"),
                "required"
            ],
            ]);
        $filename="images/no_image.png";
        if(request()->image)
        {   $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/brands/'), $filename);
            $filename='images/brands/' . $filename;
        }
        $input = [

            "name_en" => $request->name_en,
            "parent_brand_id" => $request->parent_brand_id,
            
 
            "image" => $filename,
            "created_by" => Auth()->id()
        ];
        Brand::create($input);
        return redirect('/brands/index');


    }
    public function remove($id)
    {
        Brand::where('id', $id)
            ->update(array('deleted_at' => Carbon::now()));
        return redirect('/brands/index');

    }
}
