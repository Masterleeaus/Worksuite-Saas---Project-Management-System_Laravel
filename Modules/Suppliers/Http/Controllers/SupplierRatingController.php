<?php

namespace Modules\Suppliers\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\Suppliers\Entities\Supplier;
use Modules\Suppliers\Entities\SupplierRating;

class SupplierRatingController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'suppliers::app.menu.ratings';
    }

    public function index()
    {
        $viewPermission = user()->permission('view_suppliers');
        abort_403(!in_array($viewPermission, ['all']));

        $this->suppliers = Supplier::withCount('ratings')
            ->orderByDesc('fsm_rating')
            ->paginate(20);

        return view('suppliers::suppliers.ratings.index', $this->data);
    }

    public function updateRating(Request $request, int $supplierId)
    {
        $editPermission = user()->permission('manage_supplier_ratings');
        abort_403($editPermission !== 'all');

        $request->validate([
            'fsm_rating'         => 'nullable|integer|min:1|max:5',
            'fsm_lead_time_days' => 'nullable|integer|min:0|max:365',
            'fsm_payment_terms'  => 'nullable|string|max:191',
        ]);

        $supplier = Supplier::findOrFail($supplierId);
        $supplier->fsm_rating         = $request->input('fsm_rating');
        $supplier->fsm_lead_time_days = $request->input('fsm_lead_time_days');
        $supplier->fsm_payment_terms  = $request->input('fsm_payment_terms');
        $supplier->save();

        if ($request->ajax()) {
            return response()->json(['status' => 'success']);
        }

        return redirect()->route('suppliers.ratings.index')->with('success', __('messages.recordUpdate'));
    }
}
