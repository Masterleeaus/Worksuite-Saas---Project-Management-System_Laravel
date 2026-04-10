<?php

namespace App\Http\Controllers\Web\v1;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        $customers=Customer::all();
       return view("admin.customers.customers-list")->withCustomers($customers);
    }

    public function any_data()
    {
        return datatables()->of(Customer::select(['*'])->orderBy('id', 'desc'))
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('Y-m-d H:i');
            })->addColumn('image', function ($item) {
                return url('/') . "/" . $item->image;
            }) ->addColumn('contacted_by_name', function ($item) {
                return  $item->contactedBy->name??"";
            }) ->addColumn('contacted_by_phone', function ($item) {
                return  $item->contactedBy->mobile??"";
            }) ->addColumn('action', function ($item) {
            return '<div class="btn-group">'.(!Auth()->user()->hasRole("admin")?'':'<form  action='.url("customers/remove/" .$item->id) . '>
            <input type="hidden" name="_method" value="">
            <button   style="margin-right:5px;margin-left:5px;" type="submit" name="submit" value="" class="btn btn-outline-danger" onClick="return confirm(\'Are you sure you want to delete customer?\')"">
            <i class="fa fa-trash-o"></i>
</button>
             </form>' ). (!Auth()->user()->hasRole("admin")?'':'<form  action='.url("customers/request-update/" .$item->id) . '>
                  <input type="hidden" name="_method" value="">
                  <button id='.$item->id.'"     style="margin-right:5px;margin-left:5px;"   value="" class="btn btn-outline-primary" onClick="setUpdateItem('.$item->id.')">
                  <i class="fa fa-edit"></i>
 </button>
                   </form>' ).
                    ' </div>';
        }) ->make(true);

    }
    public function create()
    {
        $superVisors=User::where("app_user_type",2)->get();
        $contacts=Contact::all();
        return view("admin.customers.customers-create")->withContacts($contacts)->withSupervisors($superVisors);
    }
}
