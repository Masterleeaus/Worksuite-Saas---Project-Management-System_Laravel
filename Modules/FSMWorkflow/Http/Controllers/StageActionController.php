<?php

namespace Modules\FSMWorkflow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;
use Modules\FSMWorkflow\Models\FSMStageAction;
use Modules\FSMWorkflow\Services\StageActionService;

class StageActionController extends Controller
{
    /**
     * List all stage actions for a given stage.
     */
    public function index(int $stageId)
    {
        $stage   = FSMStage::findOrFail($stageId);
        $actions = FSMStageAction::forStage($stageId)->orderBy('sequence')->get();

        $activityTypes = $this->getActivityTypes();

        return view('fsmworkflow::stage_actions.index', compact('stage', 'actions', 'activityTypes'));
    }

    public function create(int $stageId)
    {
        $stage         = FSMStage::findOrFail($stageId);
        $activityTypes = $this->getActivityTypes();

        return view('fsmworkflow::stage_actions.create', compact('stage', 'activityTypes'));
    }

    public function store(Request $request, int $stageId)
    {
        FSMStage::findOrFail($stageId);

        $data = $request->validate([
            'name'             => 'nullable|string|max:255',
            'action_type'      => 'required|string|in:' . implode(',', array_keys(FSMStageAction::ACTION_TYPES)),
            'template_id'      => 'nullable|integer',
            'activity_type_id' => 'nullable|integer',
            'webhook_url'      => 'nullable|url|max:2048',
            'condition'        => 'nullable|string',
            'custom_payload'   => 'nullable|string',
            'sequence'         => 'nullable|integer|min:0',
            'active'           => 'nullable|boolean',
        ]);

        $data['stage_id'] = $stageId;
        $data['active']   = $request->boolean('active', true);

        FSMStageAction::create($data);

        return redirect()->route('fsmworkflow.stage_actions.index', $stageId)
            ->with('success', 'Stage action created.');
    }

    public function edit(int $stageId, int $id)
    {
        $stage         = FSMStage::findOrFail($stageId);
        $action        = FSMStageAction::forStage($stageId)->findOrFail($id);
        $activityTypes = $this->getActivityTypes();

        return view('fsmworkflow::stage_actions.edit', compact('stage', 'action', 'activityTypes'));
    }

    public function update(Request $request, int $stageId, int $id)
    {
        FSMStage::findOrFail($stageId);
        $action = FSMStageAction::forStage($stageId)->findOrFail($id);

        $data = $request->validate([
            'name'             => 'nullable|string|max:255',
            'action_type'      => 'required|string|in:' . implode(',', array_keys(FSMStageAction::ACTION_TYPES)),
            'template_id'      => 'nullable|integer',
            'activity_type_id' => 'nullable|integer',
            'webhook_url'      => 'nullable|url|max:2048',
            'condition'        => 'nullable|string',
            'custom_payload'   => 'nullable|string',
            'sequence'         => 'nullable|integer|min:0',
            'active'           => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active', true);

        $action->update($data);

        return redirect()->route('fsmworkflow.stage_actions.index', $stageId)
            ->with('success', 'Stage action updated.');
    }

    public function destroy(int $stageId, int $id)
    {
        FSMStage::findOrFail($stageId);
        FSMStageAction::forStage($stageId)->findOrFail($id)->delete();

        return redirect()->route('fsmworkflow.stage_actions.index', $stageId)
            ->with('success', 'Stage action deleted.');
    }

    /**
     * Test-fire a single action against a specific order.
     */
    public function testFire(Request $request, int $stageId, int $id)
    {
        FSMStage::findOrFail($stageId);
        $action = FSMStageAction::forStage($stageId)->findOrFail($id);

        $data    = $request->validate(['order_id' => 'required|integer|exists:fsm_orders,id']);
        $order   = FSMOrder::with(['stage', 'person', 'location'])->findOrFail($data['order_id']);

        app(StageActionService::class)->fireAction($action, $order);

        return redirect()->route('fsmworkflow.stage_actions.index', $stageId)
            ->with('success', "Action '{$action->name}' test-fired against order #{$order->name}.");
    }

    // ── helpers ───────────────────────────────────────────────────────────────

    private function getActivityTypes(): \Illuminate\Support\Collection
    {
        if (class_exists(\Modules\FSMActivity\Models\FSMActivityType::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_activity_types')
        ) {
            return \Modules\FSMActivity\Models\FSMActivityType::where('active', true)->orderBy('name')->get();
        }
        return collect();
    }
}
