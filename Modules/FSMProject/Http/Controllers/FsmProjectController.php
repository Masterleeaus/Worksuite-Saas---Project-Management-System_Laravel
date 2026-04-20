<?php

namespace Modules\FSMProject\Http\Controllers;

use App\Models\Project;
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
        $data = $request->validate([
            'project_id' => 'required|integer',
            'task_id'    => 'nullable|integer',
        ]);

        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);
        $project = $this->findCompanyProject((int) $data['project_id']);
        $order->project()->associate($project);
        $order->task_id = $data['task_id'] ?? null;
        $order->save();

        return response()->json(['success' => true]);
    }

    /** Unlink an FSM order from a project / task */
    public function unlink(Request $request, int $orderId)
    {
        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);
        $order->project()->dissociate();
        $order->task_id = null;
        $order->save();

        if (! $request->expectsJson()) {
            return redirect()->back()->with('success', 'Project link removed.');
        }

        return response()->json(['success' => true]);
    }

    /** Orders belonging to a project */
    public function byProject(int $projectId)
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasTable('projects') || ! Schema::hasColumn('fsm_orders', 'project_id')) {
            return response()->json(['data' => []]);
        }

        $companyId = $this->companyId();
        $project = $this->findCompanyProject($projectId);

        $orders = $project->fsmOrders()
            ->where('company_id', $companyId)
            ->with($this->availableOrderRelations())
            ->get();

        return response()->json(['data' => $orders]);
    }

    /** Summary of FSM orders linked to a project */
    public function summary(int $projectId)
    {
        if (! Schema::hasTable('fsm_orders') || ! Schema::hasTable('projects') || ! Schema::hasColumn('fsm_orders', 'project_id')) {
            return response()->json([
                'data' => [
                    'count' => 0,
                    'total_hours' => 0.0,
                    'completed' => 0,
                    'pending' => 0,
                    'completion_percent' => 0,
                ],
            ]);
        }

        $companyId = $this->companyId();
        $project = $this->findCompanyProject($projectId);
        $orders = $project->fsmOrders()
            ->where('company_id', $companyId)
            ->with($this->availableOrderRelations())
            ->get();

        $completed = Schema::hasTable('fsm_stages')
            ? $orders->filter(fn ($order) => (bool) optional($order->stage)->is_completion_stage)->count()
            : $orders->filter(fn ($order) => !empty($order->date_end))->count();
        $count = $orders->count();
        $totalHours = round($orders->sum(function ($order) {
            if (empty($order->date_start) || empty($order->date_end)) {
                return 0;
            }

            return $order->date_start->diffInMinutes($order->date_end) / 60;
        }), 2);

        return response()->json([
            'data' => [
                'count' => $count,
                'total_hours' => $totalHours,
                'completed' => $completed,
                'pending' => max($count - $completed, 0),
                'completion_percent' => $count > 0 ? (int) round(($completed / $count) * 100) : 0,
            ],
        ]);
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int) $user->company_id;
    }

    private function findCompanyProject(int $projectId): Project
    {
        abort_if(!Schema::hasTable('projects'), 404);

        return Project::query()
            ->where('company_id', $this->companyId())
            ->findOrFail($projectId);
    }

    private function availableOrderRelations(): array
    {
        $relations = [];

        if (Schema::hasTable('fsm_stages')) {
            $relations[] = 'stage';
        }
        if (Schema::hasTable('fsm_locations')) {
            $relations[] = 'location';
        }
        if (Schema::hasTable('users')) {
            $relations[] = 'person';
        }

        return $relations;
    }
}
