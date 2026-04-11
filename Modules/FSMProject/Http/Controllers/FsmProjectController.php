<?php

namespace Modules\FSMProject\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\FSMCore\Entities\FsmOrder;

class FsmProjectController extends Controller
{
    /** Link an FSM order to a project / task */
    public function link(Request $request, int $orderId)
    {
        $request->validate([
            'project_id' => 'required|integer',
            'task_id'    => 'nullable|integer',
        ]);

        $order = FsmOrder::findOrFail($orderId);
        $data = ['project_id' => $request->project_id];
        if ($request->filled('task_id')) {
            $data['task_id'] = $request->task_id;
        }
        $order->update($data);

        return response()->json(['success' => true]);
    }

    /** Orders belonging to a project */
    public function byProject(int $projectId)
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasColumn('fsm_orders', 'project_id')) {
            return response()->json(['data' => []]);
        }

        $orders = FsmOrder::where('project_id', $projectId)
            ->with(['stage', 'location'])
            ->get();

        return response()->json(['data' => $orders]);
    }
}
