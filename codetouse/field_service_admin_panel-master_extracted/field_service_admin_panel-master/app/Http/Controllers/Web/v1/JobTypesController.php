<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\JobType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobTypesController extends Controller
{
    public function index()
    {
        $job_types=JobType::all();
         return view("admin.constants.job-types.job-types-list")
         ->withJobTypes($job_types);

    }

    public function any_data()
    {
        return datatables()->of(JobType::select(['*'])->orderBy('id', 'desc'))
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })->addColumn('image', function ($item) {
            return url('/') . "/" . $item->image;
        })->addColumn('created_by', function ($item) {
            return  $item->createdBy->name;
        })->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("job-types/remove/" . $item->id) . '>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete job type?\')"">
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
             "u_job_type" => [
                Rule::exists("job_types", "id"),
                "required"
            ],
            "u_image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $parentBrand = JobType::find($request->u_job_type);
         $parentBrand->name_en = $request->u_name_en;
          if ($_FILES["u_image"]["size"] > 0) {
            $filename = time() . '.' . request()->u_image->getClientOriginalExtension();
            request()->u_image->move(public_path('images/job_types/'), $filename);
            $parentBrand["image"] = 'images/job_types/' . $filename;

        }

        $parentBrand->save();
        return redirect('/job-types/index');


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
        $request->image->move(public_path('images/job_types/'), $filename);
        $filename='images/job_types/' . $filename;
    }
        $input = [

            "name_en" => $request->name_en,
 
            "image" => $filename,
            "created_by" => Auth()->id()
        ];
        JobType::create($input);
        return redirect('/job-types/index');


    }

    public function remove($id)
    {
        JobType::where('id', $id)
            ->update(array('deleted_at' => Carbon::now()));
        return redirect('/job-types/index');

    }
}
