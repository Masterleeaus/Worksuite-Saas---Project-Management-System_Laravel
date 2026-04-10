<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Supplier;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Supplier::latest()->paginate(20);
        return view('inventory::suppliers.index', compact('suppliers'));
    }
    public function create() { return view('inventory::suppliers.create'); }
    public function store(Request $r) {
        $data = $r->validate([
            'name'=>'required|max:190',
            'email'=>'nullable|email',
            'phone'=>'nullable|max:50',
            'abn'=>'nullable|max:50',
            'notes'=>'nullable|string'
        ]);
        Supplier::create($data);
        return redirect()->route('inventory.suppliers.index')->with('status','Supplier created');
    }
    public function edit(Supplier $supplier) {
        return view('inventory::suppliers.edit', compact('supplier'));
    }
    public function update(Request $r, Supplier $supplier) {
        $data = $r->validate([
            'name'=>'required|max:190',
            'email'=>'nullable|email',
            'phone'=>'nullable|max:50',
            'abn'=>'nullable|max:50',
            'notes'=>'nullable|string'
        ]);
        $supplier->update($data);
        return back()->with('status','Supplier updated');
    }
    public function destroy(Supplier $supplier) {
        $supplier->delete();
        return back()->with('status','Supplier deleted');
    }
}
