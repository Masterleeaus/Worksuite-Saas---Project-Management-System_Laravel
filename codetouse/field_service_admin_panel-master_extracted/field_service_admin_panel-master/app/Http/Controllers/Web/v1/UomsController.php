<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\Uom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UomsController extends Controller
{
    public function index()
    {
        $uoms=Uom::all();
         return view("admin.constants.uoms.uoms-list")
         ->withUoms($uoms);

    }
    public function any_data()
    {
        return datatables()->of(Uom::select(['*'])->orderBy('id', 'desc'))
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })->addColumn('image', function ($item) {
            return url('/') . "/" . $item->image;
        })->addColumn('created_by', function ($item) {
            return  $item->createdBy->name;
        })->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("uoms/remove/" . $item->id) . '>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete uom type?\')"">
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
             "u_uom" => [
                Rule::exists("uoms", "id"),
                "required"
            ],
            "u_image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $uom = Uom::find($request->u_uom);
         $uom->name_en = $request->u_name_en;
          if ($_FILES["u_image"]["size"] > 0) {
            $filename = time() . '.' . request()->u_image->getClientOriginalExtension();
            request()->u_image->move(public_path('images/uoms/'), $filename);
            $uom["image"] = 'images/uoms/' . $filename;

        }

        $uom->save();
        return redirect('/uoms/index');


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
        $request->image->move(public_path('images/uoms/'), $filename);
        $filename='images/uoms/' . $filename;
    }
        $input = [

            "name_en" => $request->name_en,
 
            "image" => $filename,
            "created_by" => Auth()->id()
        ];
        Uom::create($input);
        return redirect('/uoms/index');


    }
    public function remove($id)
    {
        Uom::where('id', $id)
            ->update(array('deleted_at' => Carbon::now()));
        return redirect('/uoms/index');

    }
}
