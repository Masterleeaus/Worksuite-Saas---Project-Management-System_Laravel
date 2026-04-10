<?php

namespace Modules\FSMSkill\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMSkill\Models\FSMEmployeeSkill;
use Modules\FSMSkill\Models\FSMOrderSkillRequirement;
use Modules\FSMSkill\Models\FSMSkill;
use Modules\FSMSkill\Services\SkillMatchService;

class DispatchController extends Controller
{
    public function __construct(private SkillMatchService $matcher) {}

    /**
     * Dispatch view: filter available cleaners by the skills required for an order.
     */
    public function index(Request $request)
    {
        $orderId = $request->integer('order_id');
        $order   = $orderId ? FSMOrder::find($orderId) : null;

        // All active workers
        $workers = \App\Models\User::orderBy('name')->get();

        // Skill filter (sidebar)
        $skills = FSMSkill::where('active', true)->with(['skillType', 'levels'])->orderBy('name')->get();

        $qualifiedWorkers = collect();
        $matchResults     = [];

        if ($order) {
            foreach ($workers as $worker) {
                $result = $this->matcher->checkOrderMatch($worker->id, $order->id);
                $matchResults[$worker->id] = $result;
                if ($result['match']) {
                    $qualifiedWorkers->push($worker);
                }
            }
        }

        return view('fsmskill::dispatch.index', compact(
            'order', 'workers', 'skills', 'qualifiedWorkers', 'matchResults'
        ));
    }
}
