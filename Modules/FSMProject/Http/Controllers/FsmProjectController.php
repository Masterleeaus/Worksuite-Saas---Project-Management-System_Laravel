<?php

namespace Modules\FSMProject\Http\Controllers;

use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
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

        $tasksByOrder = $this->tasksByOrder($orders);

        return view('fsmproject::links.create', compact('orders', 'tasksByOrder'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer|exists:fsm_orders,id',
            'project_id' => 'required|integer',
            'task_id' => 'nullable|integer',
        ]);

        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail((int) $data['order_id']);
        $project = $this->findCompanyProject((int) $data['project_id']);
        $order->project()->associate($project);
        $this->assignTaskToOrder($order, isset($data['task_id']) ? (int) $data['task_id'] : null);
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
        $projectTasks = $this->tasksForProject($order->project_id);

        return view('fsmproject::links.edit', compact('order', 'orders', 'projectTasks'));
    }

    public function update(Request $request, int $orderId)
    {
        $data = $request->validate([
            'project_id' => 'required|integer',
            'task_id' => 'nullable|integer',
        ]);

        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);
        $project = $this->findCompanyProject((int) $data['project_id']);
        $order->project()->associate($project);
        $this->assignTaskToOrder($order, isset($data['task_id']) ? (int) $data['task_id'] : null);
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
        return $this->linkTask($request, $orderId);
    }

    public function linkTask(Request $request, int $orderId): JsonResponse
    {
        $data = $request->validate([
            'project_id' => 'nullable|integer',
            'task_id' => 'nullable|integer|exists:tasks,id',
        ]);

        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);

        if (!empty($data['project_id'])) {
            $project = $this->findCompanyProject((int) $data['project_id']);
            $order->project()->associate($project);
        }

        $this->assignTaskToOrder($order, isset($data['task_id']) ? (int) $data['task_id'] : null);
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

    private function assignTaskToOrder(FSMOrder $order, ?int $taskId): void
    {
        if ($taskId !== null) {
            throw_if(
                empty($order->project_id),
                ValidationException::withMessages([
                    'task_id' => 'The selected task must belong to the same project as the order.',
                ])
            );

            $exists = DB::table('tasks')
                ->where('id', $taskId)
                ->where('project_id', (int) $order->project_id)
                ->when(Schema::hasColumn('tasks', 'deleted_at'), fn ($query) => $query->whereNull('deleted_at'))
                ->exists();

            throw_if(
                !$exists,
                ValidationException::withMessages([
                    'task_id' => 'The selected task must belong to the same project as the order.',
                ])
            );
        }

        $order->task_id = $taskId;
        $this->syncTaskStatusIfConfigured($order);
    }

    private function syncTaskStatusIfConfigured(FSMOrder $order): void
    {
        if (!config('fsmproject.sync_task_status_on_order_completion', false) || empty($order->task_id) || empty($order->date_end) || !Schema::hasTable('tasks')) {
            return;
        }

        $updates = [];

        if (Schema::hasColumn('tasks', 'status')) {
            $updates['status'] = 'completed';
        }
        if (Schema::hasColumn('tasks', 'completed_on')) {
            $updates['completed_on'] = now();
        }

        if (!empty($updates)) {
            DB::table('tasks')->where('id', (int) $order->task_id)->update($updates);
        }
    }

    private function tasksByOrder(Collection $orders): array
    {
        return $orders
            ->mapWithKeys(function (FSMOrder $order) {
                return [$order->id => $this->tasksForProject($order->project_id)->values()];
            })
            ->all();
    }

    private function tasksForProject(?int $projectId): Collection
    {
        if (empty($projectId) || !Schema::hasTable('tasks') || !Schema::hasColumn('tasks', 'project_id')) {
            return collect();
        }

        $query = DB::table('tasks')
            ->select(['id', 'project_id', 'heading'])
            ->where('project_id', $projectId)
            ->orderBy('heading');

        if (Schema::hasColumn('tasks', 'company_id')) {
            $query->where(function ($builder) {
                $builder->where('company_id', $this->companyId())
                    ->orWhereNull('company_id');
            });
        }

        if (Schema::hasColumn('tasks', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        return $query->get();
    }
}
