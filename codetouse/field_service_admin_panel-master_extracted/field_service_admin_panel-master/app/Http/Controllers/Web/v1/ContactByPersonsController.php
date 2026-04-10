<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\Contact;
 use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactByPersonsController extends Controller
{
    public function index()
    {
        $contact_by_persons=Contact::all();
         return view("admin.contact-by-persons.contact-by-persons-list")
         ->withContactByPersons($contact_by_persons);

    }

    public function any_data()
    {
        return datatables()->of(Contact::select(['*'])->orderBy('id', 'desc'))
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })->addColumn('image', function ($item) {
            return url('/') . "/" . $item->image;
        })->addColumn('created_by', function ($item) {
            return  $item->createdBy->name;
        })->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("contact-by-persons/remove/" . $item->id) . '>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete this person?\')"">
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
            "u_name" => "required",
            "u_mobile" => "required",
            "u_official_number" => "required",
            "u_official_email" => "required",
            "u_personal_email" => "required",
            
             "u_person_id" => [
                Rule::exists("contacts", "id"),
                "required"
            ],
            "u_image" => 'mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        $contactByPerson = Contact::find($request->u_person_id);
        $contactByPerson->name = $request->u_name;
        $contactByPerson->mobile = $request->u_mobile;
        $contactByPerson->official_number = $request->u_official_number;
        $contactByPerson->official_email = $request->u_official_email;
        $contactByPerson->personal_email = $request->u_personal_email;
         
        //   if ($_FILES["u_image"]["size"] > 0) {
        //     $filename = time() . '.' . request()->u_image->getClientOriginalExtension();
        //     request()->u_image->move(public_path('images/parend_brands/'), $filename);
        //     $parentBrand["image"] = 'images/parend_brands/' . $filename;

        // }

        $contactByPerson->save();
        return redirect('/contact-by-persons/index');


    }

    public function add(Request $request)
    {

        $request->validate([
            "name" => "required",
            "mobile" => "required",
            "official_number" => "required",
            "official_email" => "required",
            "personal_email" => "required",
            ]);
        $filename="images/no_image.png";
        if(request()->image)
     {   $filename = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('images/parend_brands/'), $filename);
        $filename='images/parend_brands/' . $filename;
    }
        $input = [

        "name" => $request->name,
        "mobile" => $request->mobile,
        "official_number" => $request->official_number,
        "official_email" => $request->official_email,
        "personal_email" => $request->personal_email,
        "created_by" => Auth()->id()
        ];
        Contact::create($input);
        return redirect('/contact-by-persons/index');


    }
    public function remove($id)
    {
        Contact::where('id', $id)
            ->update(array('deleted_at' => Carbon::now()));
        return redirect('/contact-by-persons/index');

    }
}
