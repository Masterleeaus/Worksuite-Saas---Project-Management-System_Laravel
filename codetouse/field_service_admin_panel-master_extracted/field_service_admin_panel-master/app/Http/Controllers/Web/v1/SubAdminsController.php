<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubAdminsController extends Controller
{
    public function index()
    {
        $subAdmins=User::where("app_user_type",2)->get();
       return view("admin.sub_admins.sub-admins-list")->withSubAdmins($subAdmins);
    }
    public function create()
    {
        return view("admin.sub_admins.sub-admins-create");
    }
    public function any_data()
    {
        return datatables()->of(User::where("app_user_type",2)->select(['*'])->orderBy('id', 'desc'))
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })->addColumn('image', function ($item) {
            return url('/') . "/" . $item->image;
        }) ->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("sub-admins/remove/" .$item->id) . '>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete super admin?\')"">
            <i class="fa fa-trash-o"></i>
</button>
             </form>' ). (!Auth()->user()->hasRole("admin")?'':'<form  action='.url("sub-admins/request-update/" .$item->id) . '>
                  <input type="hidden" name="_method" value="">
                  <button id='.$item->id.'"     style="margin-right:5px;margin-left:5px;"   value="" class="btn btn-outline-primary" onClick="setUpdateItem('.$item->id.')">
                  <i class="fa fa-edit"></i>
 </button>
                   </form>' ).
                    ' </div>';
        }) ->make(true);

    }
   
    public function request_update($id)
    {
        $user=User::find($id);
        
        return view("admin.sub_admins.sub-admins-update")->withSubAdmin($user);

    }
    public function update(Request $request)
    {
        $request->validate([
            "name" => "required",
            "password" => "required",
            "email" => "required",
            "contact" => "required",
            "sub_admin" => [
                Rule::exists("users", "id"),
                "required"
            ],
            "image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $sub_admin = User::find($request->sub_admin);
        $sub_admin->name = $request->name;
        $sub_admin->contact = $request->contact;
        
        $sub_admin->email = $request->email;
        $sub_admin->password = bcrypt($request->password);
        
        if ($_FILES["image"]["size"] > 0) {
            $filename = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/users/'), $filename);
            $sub_admin["image"] = 'images/users/' . $filename;

        }
        $sub_admin->save();
        return redirect('/sub-admins/index');
    }

    public function store(Request $request)
    {

        $request->validate([
            "email" => "required|unique:users,email",

            "name" => "required",
            "password" => "required",
            "contact" => "required",
            
            
            
             "image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
            ]);
        $filename="images/no_image.png";
        if(request()->image)
     {   $filename = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('images/users/'), $filename);
        $filename='images/users/' . $filename;
    }
        $input = [

            "name" => $request->name,
            "contact" => $request->contact,
            
            "email" => $request->email,
            "password"=>bcrypt($request->password),
            "image" => $filename,
            "app_user_type"=>2,
            "created_by" => Auth()->id(),
        ];
     $user=   User::create($input);
     $user->assignRole('sub_admin');

        return redirect('/sub-admins/index');


    }

    public function remove($id)
    {
        User::where('id', $id)
            ->update(array('deleted_at' => Carbon::now()));
        return redirect('/sub-admins/index');

    }
}
