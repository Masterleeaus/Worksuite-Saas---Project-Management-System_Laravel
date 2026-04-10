<?php

namespace Modules\FSMSkill\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMSkill\Models\FSMOrderSkillRequirement;
use Modules\FSMSkill\Models\FSMSkill;
use Modules\FSMSkill\Models\FSMSkillLevel;
use Modules\FSMSkill\Services\SkillMatchService;

class OrderSkillController extends Controller
{
    public function __construct(private SkillMatchService $matcher) {}

    public function index(int $orderId)
    {
        $order        = FSMOrder::findOrFail($orderId);
        $requirements = FSMOrderSkillRequirement::with(['skill.skillType', 'skillLevel'])
            ->where('fsm_order_id', $orderId)
            ->get();
        $skills = FSMSkill::where('active', true)->with(['skillType', 'levels'])->orderBy('name')->get();

        return view('fsmskill::order_skills.index', compact('order', 'requirements', 'skills'));
    }

    public function store(Request $request, int $orderId)
    {
        FSMOrder::findOrFail($orderId);

        $data = $request->validate([
            'skill_id'       => 'required|integer|exists:fsm_skills,id',
            'skill_level_id' => 'nullable|integer|exists:fsm_skill_levels,id',
        ]);

        FSMOrderSkillRequirement::updateOrCreate(
            ['fsm_order_id' => $orderId, 'skill_id' => $data['skill_id']],
            ['skill_level_id' => $data['skill_level_id'] ?? null]
        );

        return redirect()->route('fsmskill.order-skills.index', $orderId)
            ->with('success', 'Skill requirement added.');
    }

    public function destroy(int $orderId, int $id)
    {
        $req = FSMOrderSkillRequirement::where('fsm_order_id', $orderId)->findOrFail($id);
        $req->delete();

        return redirect()->route('fsmskill.order-skills.index', $orderId)
            ->with('success', 'Skill requirement removed.');
    }

    /**
     * AJAX endpoint: validate whether a given worker meets the order's skill requirements.
     * POST /fsm/skills/orders/{orderId}/validate-worker
     * Body: { user_id: int }
     */
    public function validateWorker(Request $request, int $orderId)
    {
        FSMOrder::findOrFail($orderId);

        $data   = $request->validate(['user_id' => 'required|integer']);
        $result = $this->matcher->checkOrderMatch($data['user_id'], $orderId);

        return response()->json($result);
    }
}
