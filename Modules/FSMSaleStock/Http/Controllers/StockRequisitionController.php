<?php

namespace Modules\FSMSaleStock\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMSaleStock\Models\FSMStockRequisition;

class StockRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only(['status', 'fsm_order_id']);

        $requisitions = FSMStockRequisition::with(['fsmOrder', 'invoice', 'requestedBy'])
            ->when($filter['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filter['fsm_order_id'] ?? null, fn($q, $v) => $q->where('fsm_order_id', $v))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $statuses = FSMStockRequisition::$statuses;

        return view('fsmsalestock::requisitions.index', compact('requisitions', 'filter', 'statuses'));
    }

    public function create()
    {
        $orders   = FSMOrder::orderBy('name')->get();
        $invoices = Invoice::orderByDesc('issue_date')->get();
        $statuses = FSMStockRequisition::$statuses;

        return view('fsmsalestock::requisitions.create', compact('orders', 'invoices', 'statuses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fsm_order_id'   => 'nullable|integer|exists:fsm_orders,id',
            'invoice_id'     => 'nullable|integer|exists:invoices,id',
            'status'         => 'required|in:' . implode(',', array_keys(FSMStockRequisition::$statuses)),
            'notes'          => 'nullable|string',
            'requested_date' => 'nullable|date',
        ]);

        $data['requested_by'] = auth()->id();
        FSMStockRequisition::create($data);

        return redirect()->route('fsmsalestock.index')
            ->with('success', 'Stock requisition created.');
    }

    public function show(int $id)
    {
        $requisition = FSMStockRequisition::with(['fsmOrder', 'invoice', 'requestedBy', 'lines'])->findOrFail($id);
        $statuses = FSMStockRequisition::$statuses;

        return view('fsmsalestock::requisitions.show', compact('requisition', 'statuses'));
    }

    public function edit(int $id)
    {
        $requisition = FSMStockRequisition::with('lines')->findOrFail($id);
        $orders   = FSMOrder::orderBy('name')->get();
        $invoices = Invoice::orderByDesc('issue_date')->get();
        $statuses = FSMStockRequisition::$statuses;

        return view('fsmsalestock::requisitions.edit', compact('requisition', 'orders', 'invoices', 'statuses'));
    }

    public function update(Request $request, int $id)
    {
        $requisition = FSMStockRequisition::findOrFail($id);

        $data = $request->validate([
            'fsm_order_id'   => 'nullable|integer|exists:fsm_orders,id',
            'invoice_id'     => 'nullable|integer|exists:invoices,id',
            'status'         => 'required|in:' . implode(',', array_keys(FSMStockRequisition::$statuses)),
            'notes'          => 'nullable|string',
            'requested_date' => 'nullable|date',
        ]);

        $requisition->update($data);

        return redirect()->route('fsmsalestock.index')
            ->with('success', 'Stock requisition updated.');
    }

    public function destroy(int $id)
    {
        FSMStockRequisition::findOrFail($id)->delete();
        return redirect()->route('fsmsalestock.index')->with('success', 'Stock requisition deleted.');
    }

    public function submit(int $id)
    {
        FSMStockRequisition::findOrFail($id)->update(['status' => FSMStockRequisition::STATUS_SUBMITTED]);
        return back()->with('success', 'Requisition submitted.');
    }

    public function fulfill(int $id)
    {
        FSMStockRequisition::findOrFail($id)->update(['status' => FSMStockRequisition::STATUS_FULFILLED]);
        return back()->with('success', 'Requisition marked fulfilled.');
    }

    public function bill(int $id)
    {
        FSMStockRequisition::findOrFail($id)->update(['status' => FSMStockRequisition::STATUS_BILLED]);
        return back()->with('success', 'Requisition marked billed.');
    }
}
