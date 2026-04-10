<?php

namespace Modules\FSMStock\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMStock\Models\FSMOrderStockLine;
use Modules\FSMStock\Models\FSMStockItem;
use Modules\FSMStock\Models\FSMStockMove;

class OrderStockLineController extends Controller
{
    public function index($orderId)
    {
        $order    = FSMOrder::findOrFail($orderId);
        $lines    = FSMOrderStockLine::with('product')
            ->where('fsm_order_id', $orderId)
            ->get();
        $products = FSMStockItem::where('active', true)->orderBy('name')->get();

        return view('fsmstock::order_stock_lines.index', compact('order', 'lines', 'products'));
    }

    public function store(Request $request, $orderId)
    {
        FSMOrder::findOrFail($orderId);

        $data = $request->validate([
            'product_id'  => 'required|integer|exists:fsm_stock_items,id',
            'qty_planned' => 'required|numeric|min:0.001',
            'billable'    => 'nullable|boolean',
            'notes'       => 'nullable|string',
        ]);

        FSMOrderStockLine::create(array_merge($data, [
            'fsm_order_id' => $orderId,
            'state'        => FSMOrderStockLine::STATE_PLANNED,
            'billable'     => $request->boolean('billable', false),
        ]));

        return redirect()->back()->with('success', 'Stock line added.');
    }

    public function update(Request $request, $id)
    {
        $line = FSMOrderStockLine::findOrFail($id);
        $data = $request->validate([
            'qty_used' => 'nullable|numeric|min:0',
            'state'    => 'nullable|in:planned,consumed,returned',
            'notes'    => 'nullable|string',
            'billable' => 'nullable|boolean',
        ]);
        if ($request->has('billable')) {
            $data['billable'] = $request->boolean('billable');
        }
        $line->update($data);

        return redirect()->back()->with('success', 'Stock line updated.');
    }

    public function destroy($id)
    {
        FSMOrderStockLine::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Stock line deleted.');
    }

    public function consume(Request $request, $id)
    {
        $line = FSMOrderStockLine::findOrFail($id);

        $data = $request->validate([
            'qty_used' => 'required|numeric|min:0',
        ]);

        $qtyUsed = (float) $data['qty_used'];

        $line->update([
            'state'    => FSMOrderStockLine::STATE_CONSUMED,
            'qty_used' => $qtyUsed,
        ]);

        FSMStockMove::create([
            'company_id'              => $line->company_id,
            'fsm_order_id'            => $line->fsm_order_id,
            'fsm_order_stock_line_id' => $line->id,
            'product_id'              => $line->product_id,
            'qty'                     => $qtyUsed,
            'direction'               => 'out',
            'reason'                  => 'order completion',
            'moved_by'                => auth()->id(),
            'moved_at'                => now(),
        ]);

        $product = FSMStockItem::findOrFail($line->product_id);
        $newQty  = max(0, $product->current_qty - $qtyUsed);

        if ($qtyUsed > $line->qty_planned) {
            $shortfall = $qtyUsed - $line->qty_planned;
            $product->update(['current_qty' => $newQty]);
            return redirect()->back()
                ->with('success', "Consumed. Note: quantity used exceeded planned by {$shortfall}.");
        }

        $product->update(['current_qty' => $newQty]);

        return redirect()->back()->with('success', 'Stock line consumed and inventory updated.');
    }
}
