<?php

namespace Modules\FSMStock\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\FSMStock\Models\FSMStockItem;
use Modules\FSMStock\Models\FSMStockMove;

class StockReportController extends Controller
{
    public function reorder()
    {
        $items = FSMStockItem::whereRaw('current_qty < min_qty')
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('fsmstock::reports.reorder', compact('items'));
    }

    public function export()
    {
        $moves = FSMStockMove::with(['product', 'order', 'mover'])->latest('moved_at')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="stock_report.csv"',
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
