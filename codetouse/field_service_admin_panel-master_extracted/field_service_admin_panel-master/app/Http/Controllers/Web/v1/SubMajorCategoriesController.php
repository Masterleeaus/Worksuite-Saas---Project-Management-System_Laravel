<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\MajorCategory;
use App\Models\SubMajorCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubMajorCategoriesController extends Controller
{
    public function index()
    {
        $sub_major_categories=SubMajorCategory::all();
        $major_categories=MajorCategory::all();
        
         return view("admin.constants.sub-major-categories.sub-major-categories-list")
         ->withSubMajorCategories($sub_major_categories)
         ->withMajorCategories($major_categories);

    }

    public function any_data()
    {
        return datatables()->of(SubMajorCategory::select(['*'])->orderBy('id', 'desc'))
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })->addColumn('image', function ($item) {
            return url('/') . "/" . $item->image;
        })->addColumn('created_by', function ($item) {
            return  $item->createdBy->name;
        })->addColumn('major_category_name', function ($item) {
            return  $item->majorCategory->name_en;
        })->addColumn('major_category_id', function ($item) {
            return  $item->majorCategory->id;
        })->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("sub-major-categories/remove/" . $item->id) . '>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete Sub Major Category?\')"">
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
            "sub_major_category" => [
                Rule::exists("sub_major_categories", "id"),
                "required"
            ],
            "u_major_category_id" => [
                Rule::exists("major_categories", "id"),
                "required"
            ],
            "u_image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $subMajorCategory = SubMajorCategory::find($request->sub_major_category);
        $subMajorCategory->name_en = $request->u_name_en;
        $subMajorCategory->major_category_id = $request->u_major_category_id;
         
          if ($_FILES["u_image"]["size"] > 0) {
            $filename = time() . '.' . request()->u_image->getClientOriginalExtension();
            request()->u_image->move(public_path('images/sub_major_categories/'), $filename);
            $subMajorCategory["image"] = 'images/sub_major_categories/' . $filename;

        }

        $subMajorCategory->save();
        return redirect('/sub-major-categories/index');


    }

    public function add(Request $request)
    {

        $request->validate([
            "name_en" => "required",
             "image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
             "major_category_id" => [
                Rule::exists("major_categories", "id"),
                "required"
            ],
            ]);
        $filename="images/no_image.png";
        if(request()->image)
        {   $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/sub_major_category/'), $filename);
            $filename='images/sub_major_category/' . $filename;
        }
        $input = [

            "name_en" => $request->name_en,
            "major_category_id" => $request->major_category_id,
            
 
            "image" => $filename,
            "created_by" => Auth()->id()
        ];
        SubMajorCategory::create($input);
        return redirect('/sub-major-categories/index');


    }

    public function remove($id)
    {
        SubMajorCategory::where('id', $id)
            ->update(array('deleted_at' => Carbon::now()));
        return redirect('/sub-major-categories/index');

    }
}
