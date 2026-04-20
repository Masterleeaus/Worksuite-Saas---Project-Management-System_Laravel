<?php

namespace Modules\FSMProject\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Modules\FSMCore\Models\FSMOrder;

class FsmProjectController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $this->companyId();
        $filter = $request->only(['q']);

        $orders = FSMOrder::with(['location'])
            ->where('company_id', $companyId)
            ->whereNotNull('project_id')
            ->when($filter['q'] ?? null, function ($query, $term) {
                $query->where('name', 'like', '%' . trim((string) $term) . '%');
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('fsmproject::links.index', compact('orders', 'filter'));
    }

    public function create()
    {
        $companyId = $this->companyId();

        $orders = FSMOrder::where('company_id', $companyId)
            ->orderByDesc('id')
            ->limit(200)
            ->get(['id', 'name', 'project_id', 'task_id']);

        return view('fsmproject::links.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer|exists:fsm_orders,id',
            'project_id' => 'required|integer',
            'task_id' => 'nullable|integer',
        ]);

        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail((int) $data['order_id']);
        $order->project_id = $data['project_id'];
        $order->task_id = $data['task_id'] ?? null;
        $order->save();

        return redirect()->route('fsmproject.index')
            ->with('success', 'Order linked to project.');
    }

    public function edit(int $orderId)
    {
        $companyId = $this->companyId();
        $order = FSMOrder::where('company_id', $companyId)->findOrFail($orderId);
        $orders = FSMOrder::where('company_id', $companyId)
            ->orderByDesc('id')
            ->limit(200)
            ->get(['id', 'name', 'project_id', 'task_id']);

        return view('fsmproject::links.edit', compact('order', 'orders'));
    }

    public function update(Request $request, int $orderId)
    {
        $data = $request->validate([
            'project_id' => 'required|integer',
            'task_id' => 'nullable|integer',
        ]);

        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);
        $order->project_id = $data['project_id'];
        $order->task_id = $data['task_id'] ?? null;
        $order->save();

        return redirect()->route('fsmproject.index')
            ->with('success', 'Project link updated.');
    }

    public function destroy(int $orderId)
    {
        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);
        $order->project_id = null;
        $order->task_id = null;
        $order->save();

        return redirect()->route('fsmproject.index')
            ->with('success', 'Project link removed.');
    }

    /** Link an FSM order to a project / task */
    public function link(Request $request, int $orderId)
    {
        $request->validate([
            'project_id' => 'required|integer',
            'task_id'    => 'nullable|integer',
        ]);

        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);
        $data = ['project_id' => $request->project_id];
        if ($request->filled('task_id')) {
            $data['task_id'] = $request->task_id;
        }
        $order->project_id = $data['project_id'];
        $order->task_id = $data['task_id'] ?? null;
        $order->save();

        return response()->json(['success' => true]);
    }

    /** Orders belonging to a project */
    public function byProject(int $projectId)
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasColumn('fsm_orders', 'project_id')) {
            return response()->json(['data' => []]);
        }

        $orders = FSMOrder::where('company_id', $this->companyId())
            ->where('project_id', $projectId)
            ->with(['stage', 'location'])
            ->get();

        return response()->json(['data' => $orders]);
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int) $user->company_id;
    }
}
