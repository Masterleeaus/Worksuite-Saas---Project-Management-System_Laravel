<?php

namespace Modules\FSMAvailability\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMAvailability\Models\FSMAvailabilityRule;
use App\Models\User;

class AvailabilityRuleController extends Controller
{
    public function index(int $userId)
    {
        $companyId = $this->companyId();
        $worker = User::where('company_id', $companyId)->findOrFail($userId);
        $rules  = FSMAvailabilityRule::where('company_id', $companyId)
            ->where('person_id', $userId)
            ->orderByRaw("FIELD(day_of_week,'mon','tue','wed','thu','fri','sat','sun')")
            ->get();

        return view('fsmavailability::rules.index', compact('worker', 'rules'));
    }

    public function create(int $userId)
    {
        $worker = User::where('company_id', $this->companyId())->findOrFail($userId);
        $days   = FSMAvailabilityRule::$days;

        return view('fsmavailability::rules.create', compact('worker', 'days'));
    }

    public function store(Request $request, int $userId)
    {
        $companyId = $this->companyId();
        $worker = User::where('company_id', $companyId)->findOrFail($userId);

        $data = $request->validate([
            'day_of_week' => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'time_start'  => 'required|date_format:H:i',
            'time_end'    => 'required|date_format:H:i|after:time_start',
            'active'      => 'nullable|boolean',
        ]);

        $data['person_id']  = $userId;
        $data['company_id'] = $companyId;
        $data['active']     = $request->boolean('active', true);

        FSMAvailabilityRule::updateOrCreate(
            ['company_id' => $companyId, 'person_id' => $userId, 'day_of_week' => $data['day_of_week']],
            $data
        );

        return redirect()->route('fsmavailability.rules.index', $userId)
            ->with('success', 'Working-hour rule saved.');
    }

    public function edit(int $userId, int $id)
    {
        $companyId = $this->companyId();
        $worker = User::where('company_id', $companyId)->findOrFail($userId);
        $rule   = FSMAvailabilityRule::where('company_id', $companyId)->where('person_id', $userId)->findOrFail($id);
        $days   = FSMAvailabilityRule::$days;

        return view('fsmavailability::rules.edit', compact('worker', 'rule', 'days'));
    }

    public function update(Request $request, int $userId, int $id)
    {
        $rule = FSMAvailabilityRule::where('company_id', $this->companyId())->where('person_id', $userId)->findOrFail($id);

        $data = $request->validate([
            'day_of_week' => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'time_start'  => 'required|date_format:H:i',
            'time_end'    => 'required|date_format:H:i|after:time_start',
            'active'      => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active', true);
        $rule->update($data);

        return redirect()->route('fsmavailability.rules.index', $userId)
            ->with('success', 'Working-hour rule updated.');
    }

    public function destroy(int $userId, int $id)
    {
        FSMAvailabilityRule::where('company_id', $this->companyId())->where('person_id', $userId)->findOrFail($id)->delete();

        return redirect()->route('fsmavailability.rules.index', $userId)
            ->with('success', 'Rule deleted.');
    }

    private function companyId(): ?int
    {
        return auth()->user()?->company_id;
    }
}
