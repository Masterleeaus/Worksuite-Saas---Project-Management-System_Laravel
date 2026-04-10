<?php

namespace Modules\Suppliers\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SupplierRatingController extends Controller
{
    public function index()
    {
        $suppliers = DB::table('suppliers')
            ->select('id','name','email','fsm_rating','fsm_lead_time_days','fsm_payment_terms')
            ->orderByDesc('fsm_rating')
            ->paginate(20);

        return view('suppliers::suppliers.index', compact('suppliers'));
    }

    public function updateRating(Request $request, int $supplierId)
    {
        $request->validate([
            'fsm_rating' => 'nullable|integer|min:1|max:5',
            'fsm_lead_time_days' => 'nullable|integer|min:0|max:365',
            'fsm_payment_terms' => 'nullable|string|max:191',
        ]);

        DB::table('suppliers')->where('id', $supplierId)->update([
            'fsm_rating' => $request->input('fsm_rating'),
            'fsm_lead_time_days' => $request->input('fsm_lead_time_days'),
            'fsm_payment_terms' => $request->input('fsm_payment_terms'),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('status', 'Supplier updated');
    }
}
