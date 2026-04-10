<?php

namespace Modules\FSMStock\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMStock\Models\FSMStockItem;
use Modules\FSMStock\Models\FSMStockMove;

class StockMoveController extends Controller
{
    public function index()
    {
        $moves = FSMStockMove::with(['product', 'order', 'mover'])
            ->latest('moved_at')
            ->paginate(100);

        return view('fsmstock::stock_moves.index', compact('moves'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:fsm_stock_items,id',
            'qty'        => 'required|numeric|min:0.001',
            'direction'  => 'required|in:in,out',
            'reason'     => 'nullable|string|max:191',
        ]);

        FSMStockMove::create(array_merge($data, [
            'moved_by' => auth()->id(),
            'moved_at' => now(),
        ]));

        $product = FSMStockItem::findOrFail($data['product_id']);
        if ($data['direction'] === 'in') {
            $product->increment('current_qty', $data['qty']);
        } else {
            $newQty = max(0, $product->current_qty - $data['qty']);
            $product->update(['current_qty' => $newQty]);
        }

        return redirect()->route('fsmstock.stock-moves.index')
            ->with('success', 'Stock movement recorded.');
    }

    public function export()
    {
        $moves = FSMStockMove::with(['product', 'order', 'mover'])->latest('moved_at')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="stock_moves.csv"',
        ];

        $callback = function () use ($moves) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Product', 'Qty', 'Direction', 'Order', 'Worker', 'Reason', 'Moved At']);
            foreach ($moves as $move) {
                fputcsv($handle, [
                    $move->id,
                    optional($move->product)->name,
                    $move->qty,
                    $move->direction,
                    optional($move->order)->id,
                    optional($move->mover)->name,
                    $move->reason,
                    $move->moved_at,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
