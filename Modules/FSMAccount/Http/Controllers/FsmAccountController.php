<?php

namespace Modules\FSMAccount\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\FSMCore\Entities\FsmOrder;

class FsmAccountController extends Controller
{
    /** List orders with invoice status */
    public function index(Request $request)
    {
        if (! Schema::hasTable('fsm_orders')) {
            return response()->json(['data' => [], 'message' => 'FSM tables not yet migrated.']);
        }

        $orders = FsmOrder::with('stage')
            ->when(Schema::hasColumn('fsm_orders', 'invoiced'), fn($q) =>
                $q->select('id', 'name', 'invoiced', 'invoice_total', 'stage_id', 'person_id')
            )
            ->paginate(25);

        return response()->json($orders);
    }

    /** Mark an order as invoiced */
    public function markInvoiced(Request $request, int $id)
    {
        $order = FsmOrder::findOrFail($id);

        $order->update([
            'invoiced'      => true,
            'invoice_total' => $request->input('total', 0),
        ]);

        return response()->json(['success' => true, 'order' => $order->fresh()]);
    }
}
