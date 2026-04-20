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
        $order        = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);
        $requirements = FSMOrderSkillRequirement::with(['skill.skillType', 'skillLevel'])
            ->where('fsm_order_id', $order->id)
            ->get();
        $skills = FSMSkill::where('company_id', $this->companyId())->where('active', true)->with(['skillType', 'levels'])->orderBy('name')->get();

        return view('fsmskill::order_skills.index', compact('order', 'requirements', 'skills'));
    }

    public function store(Request $request, int $orderId)
    {
        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);

        $data = $request->validate([
            'skill_id'       => 'required|integer|exists:fsm_skills,id',
            'skill_level_id' => 'nullable|integer|exists:fsm_skill_levels,id',
        ]);

        FSMOrderSkillRequirement::updateOrCreate(
            ['fsm_order_id' => $order->id, 'skill_id' => $data['skill_id']],
            ['skill_level_id' => $data['skill_level_id'] ?? null]
        );

        return redirect()->route('fsmskill.order-skills.index', $orderId)
            ->with('success', 'Skill requirement added.');
    }

    public function destroy(int $orderId, int $id)
    {
        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);
        $req = FSMOrderSkillRequirement::where('fsm_order_id', $order->id)->findOrFail($id);
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
        $order = FSMOrder::where('company_id', $this->companyId())->findOrFail($orderId);

        $data   = $request->validate(['user_id' => 'required|integer']);
        \App\Models\User::where('company_id', $this->companyId())->findOrFail($data['user_id']);
        $result = $this->matcher->checkOrderMatch($data['user_id'], $order->id);

        return response()->json($result);
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int) $user->company_id;
    }
}
