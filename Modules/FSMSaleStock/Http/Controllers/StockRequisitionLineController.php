<?php

namespace Modules\FSMSaleStock\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMSaleStock\Models\FSMStockRequisition;
use Modules\FSMSaleStock\Models\FSMStockRequisitionLine;

class StockRequisitionLineController extends Controller
{
    public function store(Request $request, int $requisitionId)
    {
        FSMStockRequisition::findOrFail($requisitionId);

        $data = $request->validate([
            'product_name' => 'required|string|max:256',
            'part_number'  => 'nullable|string|max:128',
            'quantity'     => 'required|numeric|min:0.001',
            'unit_cost'    => 'required|numeric|min:0',
            'notes'        => 'nullable|string',
        ]);

        $data['requisition_id'] = $requisitionId;
        FSMStockRequisitionLine::create($data);

        return back()->with('success', 'Line item added.');
    }

    public function destroy(int $lineId)
    {
        FSMStockRequisitionLine::findOrFail($lineId)->delete();
        return back()->with('success', 'Line item removed.');
    }
}
