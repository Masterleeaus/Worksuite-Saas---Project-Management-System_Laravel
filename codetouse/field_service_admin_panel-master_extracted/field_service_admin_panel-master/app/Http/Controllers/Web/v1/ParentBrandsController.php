<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\ParentBrand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParentBrandsController extends Controller
{
    

    public function index()
    {
        $parent_brands=ParentBrand::all();
         return view("admin.constants.parent-brands.parent-brands-list")
         ->withParentBrands($parent_brands);

    }
    public function any_data()
    {
        return datatables()->of(ParentBrand::select(['*'])->orderBy('id', 'desc'))
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })->addColumn('image', function ($item) {
            return url('/') . "/" . $item->image;
        })->addColumn('created_by', function ($item) {
            return  $item->createdBy->name;
        })->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("parent-brands/remove/" . $item->id) . '>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete parent brand?\')"">
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
        })->make(true);

    }

    public function update(Request $request)
    {

         $request->validate([
            "u_name_en" => "required",
             "u_parent_brand" => [
                Rule::exists("parent_brands", "id"),
                "required"
            ],
            "u_image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $parentBrand = ParentBrand::find($request->u_parent_brand);
         $parentBrand->name_en = $request->u_name_en;
          if ($_FILES["u_image"]["size"] > 0) {
            $filename = time() . '.' . request()->u_image->getClientOriginalExtension();
            request()->u_image->move(public_path('images/parend_brands/'), $filename);
            $parentBrand["image"] = 'images/parend_brands/' . $filename;

        }

        $parentBrand->save();
        return redirect('/parent-brands/index');


    }
    public function add(Request $request)
    {

        $request->validate([
            "name_en" => "required",
             "image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
            ]);
        $filename="images/no_image.png";
        if(request()->image)
     {   $filename = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('images/parend_brands/'), $filename);
        $filename='images/parend_brands/' . $filename;
    }
        $input = [

            "name_en" => $request->name_en,
 
            "image" => $filename,
            "created_by" => Auth()->id()
        ];
        ParentBrand::create($input);
        return redirect('/parent-brands/index');


    }
    public function remove($id)
    {
        ParentBrand::where('id', $id)
            ->update(array('deleted_at' => Carbon::now()));
        return redirect('/parent-brands/index');

    }
}
