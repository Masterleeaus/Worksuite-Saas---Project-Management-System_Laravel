<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\MajorCategory;
use App\Models\ParentBrand;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubMajorCategory;
use App\Models\Uom;
use App\Models\UomType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductsController extends Controller
{
    private function productQuery(){
        return  Product::select(['*'])->with("majorCategory","subMajorCategory","subCategory","parentBrand","brand","uomType","uom","status");
    }
    public function index($type, $table, $id)
    {
        $name = null;
        if ($table == "major_category") {
            $item = MajorCategory::find($id);
            $name = $item->name_en ?? null;
        }
        else if ($table == "sub_major_category") {
            $item = SubMajorCategory::find($id);
            $name = $item->name_en ?? null;
        }
        else if ($table == "sub_category") {
            $item = SubCategory::find($id);
            $name = $item->name_en ?? null;
        }
        else if ($table == "parent_brand") {
            $item = ParentBrand::find($id);
            $name = $item->name_en ?? null;
        }
        else if ($table == "brand") {
            $item = Brand::find($id);
            $name = $item->name_en ?? null;
        }
        else if ($table == "uom_type") {
            $item = UomType::find($id);
            $name = $item->name_en ?? null;
        }
        else if ($table == "uom") {
            $item = Uom::find($id);
            $name = $item->name_en ?? null;
        }

        
        return view("admin.products.products-list")
            ->withType($type)
            ->withTable($table)
            ->withId($id)
            ->withName($name);

    }

    public function any_data($type, $table, $id)
    {

        $query = $this->productQuery();
         if ($table == "major_category") {
            $query =  $query
            ->where("major_category_id", $id);
        }
        else if ($table == "sub_major_category") {
            $query =  $query
            ->where("sub_major_category_id", $id);
        }

        else if ($table == "sub_category") {
            $query =  $query
            ->where("sub_category_id", $id);
        }

        else if ($table == "parent_brand") {
            $query =  $query
            ->where("parent_brand_id", $id);
        }

        else if ($table == "brand") {
            $query =  $query
            ->where("brand_id", $id);
        }

        else if ($table == "uom_type") {
            $query =  $query
            ->where("uom_type_id", $id);
        }
        else if ($table == "uom") {
            $query =  $query
            ->where("uom_id", $id);
        }

       
         
        return datatables()->of($query)
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');

            })->editColumn('cost_price', function ($item) {
                return number_format($item->cost_price, 3) . " OMR";
            })->editColumn('product_msrp', function ($item) {
                return number_format($item->product_msrp, 3) . " OMR";
            })->addColumn('price', function ($item) {
                return number_format($item->whole_sale_price, 3) . " OMR";
            })->editColumn('maximum_whole_sale_price', function ($item) {
                return number_format($item->maximum_whole_sale_price, 3) . " OMR";
            })->editColumn('image', function ($item) {
            return url('/') . "/" . $item->image;

        })->editColumn('status', function ($item) {
            return $item->status->name??"";

        })->editColumn('majorCategory', function ($item) {
            return $item->majorCategory->name_en??"";

        })->editColumn('subMajorCategory', function ($item) {
            return $item->subMajorCategory->name_en??"";

        })->editColumn('subCategory', function ($item) {
            return $item->subCategory->name_en??"";

        })->editColumn('parentBrand', function ($item) {
            return $item->parentBrand->name_en??"";

        })->editColumn('brand', function ($item) {
            return $item->brand->name_en??"";

        })->editColumn('uomType', function ($item) {
            return $item->uomType->name_en??"";

        })->editColumn('uom', function ($item) {
            return $item->uom->name_en??"";

        })->addColumn('availability', function ($item) {
            return  $item->stock>0?"Available":"Un-Available";
        })->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("products/remove/" . $item->id) . '/0/0>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete this product?\')"">
            <i class="fa fa-trash-o"></i>
</button>
             </form>' ).'<form  action='.url("products/show/" . $item->id) .'>
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
        })->make(true);
    }

    public function create()
    {
        $majorCategories = MajorCategory::all();
        $subMajorCategories = SubMajorCategory::all();
        $subCategories = SubCategory::all();
        $parentBrands = ParentBrand::all();
        $brands =Brand::all();
        $uomTypes =UomType::all();
        $uoms =Uom::all();
        
        return view("admin.products.products-add")
        ->withMajorCategories($majorCategories)
        ->withSubMajorCategories($subMajorCategories)
        ->withSubCategories($subCategories)
        ->withParentBrands($parentBrands)
        ->withBrands($brands)
        ->withUomTypes($uomTypes)
        ->withUoms($uoms);

    }

    public function store(Request $request)
    {

        $request->validate([

            "name" => "required",
            "major_category" => [
                Rule::exists("major_categories", "id"),
                "required"
            ],
            "sub_major_category" => [
                Rule::exists("sub_major_categories", "id"),
                "required"
            ],
            "sub_category" => [
                Rule::exists("sub_categories", "id"),
                "required"
            ],
            "parent_brand" => [
                Rule::exists("parent_brands", "id"),
                "required"
            ],
            "brand" => [
                Rule::exists("brands", "id"),
                "required"
            ],
            "uom_type" => [
                Rule::exists("uom_types", "id"),
                "required"
            ],
            "uom" => [
                Rule::exists("uoms", "id"),
                "required"
            ],
            "color" => "required",
            "cost_price" => "required",
            "purchase_price" => "required",
            "msrp" => "required",
            "whole_sale_price" => "required",
            "maximum_whole_sale_price" => "required",
            "stock" => "required",
            "image" => 'required|mimes:jpeg,png,jpg,gif,svg',
	    ]);
        $filename = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/products'), $filename);
         
        $data = $request->only(["name", "color", "sfa_code", "sku", "cost_price", "purchase_price", "whole_sale_price", "maximum_whole_sale_price","stock"]);
        $data["major_category_id"] = $request->major_category;
        $data["created_by"] = Auth()->id();
        $data["sub_major_category_id"] = $request->sub_major_category;
        $data["sub_category_id"] = $request->sub_category;
        $data["parent_brand_id"] = $request->parent_brand;
        $data["brand_id"] = $request->brand;
 
        $data["uom_type_id"] = $request->uom_type;
        $data["uom_id"] = $request->uom;

 
        $data["status_id"] = 1;
 		 

         $data["image"] = 'images/products/' . $filename;

        $product = Product::create($data);
 
        return redirect('/products/index/all/0/0');

    }
    public function show($id)
    {
        $product = $this->productQuery()->where("id",$id)->get()->first();
        
        return view("admin.products.products-show")
        ->withProduct($product)
        ->withBaseUrl(url('/') . "/")
        ->withRecentlyViewedProducts([]);

    }
    public function remove($id)
    {
        Product::where('id', $id)
            ->update(array('deleted_at' => Carbon::now()));
        return redirect('/products/index/all/0/0');

    }
}
