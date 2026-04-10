<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\SubMajorCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubCategoriesController extends Controller
{
    public function index()
    {
        $sub_categories=SubCategory::all();
        $sub_major_categories=SubMajorCategory::all();
        
         return view("admin.constants.sub-categories.sub-categories-list")
         ->withSubMajorCategories($sub_major_categories)
         ->withSubCategories($sub_categories);

    }
    public function any_data()
    {
        return datatables()->of(SubCategory::select(['*'])->orderBy('id', 'desc'))
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })->addColumn('image', function ($item) {
            return url('/') . "/" . $item->image;
        })->addColumn('created_by', function ($item) {
            return  $item->createdBy->name;
        })->addColumn('sub_major_category_name', function ($item) {
            return  $item->subMajorCategory->name_en;
        })->addColumn('sub_major_category_id', function ($item) {
            return  $item->subMajorCategory->id;
        })->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("sub-categories/remove/" . $item->id) . '>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete Sub Category?\')"">
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

         $request->validate([
            "u_name_en" => "required",
            "sub_category" => [
                Rule::exists("sub_categories", "id"),
                "required"
            ],
            "u_sub_major_category_id" => [
                Rule::exists("sub_major_categories", "id"),
                "required"
            ],
            "u_image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
        ]);
// return $request;
        $subCategory = SubCategory::find($request->sub_category);
        $subCategory->name_en = $request->u_name_en;
        $subCategory->sub_major_category_id = $request->u_sub_major_category_id;
         
          if ($_FILES["u_image"]["size"] > 0) {
            $filename = time() . '.' . request()->u_image->getClientOriginalExtension();
            request()->u_image->move(public_path('images/sub_categories/'), $filename);
            $subCategory["image"] = 'images/sub_categories/' . $filename;

        }

        $subCategory->save();
        return redirect('/sub-categories/index');


    }

    public function add(Request $request)
    {

        $request->validate([
            "name_en" => "required",
             "image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
             "sub_major_category_id" => [
                Rule::exists("sub_major_categories", "id"),
                "required"
            ],
            ]);
        $filename="images/no_image.png";
        if(request()->image)
        {   $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/sub_category/'), $filename);
            $filename='images/sub_category/' . $filename;
        }
        $input = [

            "name_en" => $request->name_en,
            "sub_major_category_id" => $request->sub_major_category_id,
            
 
            "image" => $filename,
            "created_by" => Auth()->id()
        ];
        SubCategory::create($input);
        return redirect('/sub-categories/index');


    }

    public function remove($id)
    {
        SubCategory::where('id', $id)
            ->update(array('deleted_at' => Carbon::now()));
        return redirect('/sub-categories/index');

    }
}
