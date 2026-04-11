<?php

namespace Modules\FSMStageAction\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FSMStageAction\Entities\FsmStageAction;
use Modules\FSMStageAction\Entities\FsmStageActionLog;

class FsmStageActionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            FsmStageAction::with('stage')
                ->when($request->stage_id, fn($q, $v) => $q->where('stage_id', $v))
                ->orderBy('sequence')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stage_id'    => 'required|integer|exists:fsm_stages,id',
            'name'        => 'required|string|max:191',
            'action_type' => 'required|in:send_email,send_sms,set_field,webhook,assign_worker,create_invoice',
            'active'      => 'boolean',
            'sequence'    => 'integer',
        ]);

        return response()->json(FsmStageAction::create($validated), 201);
    }

    public function update(Request $request, int $id)
    {
        $action = FsmStageAction::findOrFail($id);
        $action->update($request->only($action->getFillable()));
        return response()->json($action->fresh());
    }

    public function destroy(int $id)
    {
        FsmStageAction::findOrFail($id)->delete();
        return response()->json(['deleted' => true]);
    }

    public function logs(int $id)
    {
        return response()->json(
            FsmStageActionLog::where('stage_action_id', $id)->latest('ran_at')->take(100)->get()
        );
    }
}
