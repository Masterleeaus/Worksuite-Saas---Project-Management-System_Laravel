<?php

namespace Modules\FSMRecurring\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMTeam;
use Modules\FSMCore\Models\FSMTemplate;
use Modules\FSMCore\Models\FSMEquipment;
use Modules\FSMRecurring\Models\FSMFrequencySet;
use Modules\FSMRecurring\Models\FSMRecurring;
use Modules\FSMRecurring\Models\FSMRecurringTemplate;

class RecurringController extends Controller
{
    public function index(Request $request)
    {
        $q = FSMRecurring::query()
            ->where('company_id', $this->companyId())
            ->with(['location', 'team', 'frequencySet']);

        if ($request->filled('state')) {
            $q->where('state', $request->get('state'));
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        $recurrings = $q->orderByDesc('id')->paginate(50)->withQueryString();
        $filter     = $request->only(['state', 'q']);
        $states     = FSMRecurring::$states;

        return view('fsmrecurring::recurring.index', compact('recurrings', 'filter', 'states'));
    }

    public function create()
    {
        return view('fsmrecurring::recurring.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $equipmentIds = $data['equipment_ids'] ?? [];
        unset($data['equipment_ids']);

        // Auto-generate reference
        $last   = FSMRecurring::max('id') ?? 0;
        $prefix = config('fsmrecurring.recurring_reference_prefix', 'REC');
        $data['name'] = $prefix . '-' . str_pad((int) $last + 1, 5, '0', STR_PAD_LEFT);
        $data['company_id'] = $this->companyId();

        $recurring = FSMRecurring::create($data);
        $recurring->equipment()->sync($equipmentIds);

        return redirect()->route('fsmrecurring.recurring.show', $recurring->id)
            ->with('success', 'Recurring schedule created.');
    }

    public function show(int $id)
    {
        $recurring = FSMRecurring::where('company_id', $this->companyId())->with([
            'location', 'team', 'person', 'frequencySet', 'fsmTemplate', 'equipment',
            'recurringTemplate',
            'orders.stage', 'orders.location',
        ])->findOrFail($id);

        $preview = $recurring->previewNextOccurrences(4);

        return view('fsmrecurring::recurring.show', compact('recurring', 'preview'));
    }

    public function edit(int $id)
    {
        $recurring = FSMRecurring::where('company_id', $this->companyId())->with('equipment')->findOrFail($id);
        return view('fsmrecurring::recurring.edit', array_merge(
            $this->formData(),
            ['recurring' => $recurring]
        ));
    }

    public function update(Request $request, int $id)
    {
        $recurring = FSMRecurring::where('company_id', $this->companyId())->findOrFail($id);
        $data = $this->validated($request);
        $equipmentIds = $data['equipment_ids'] ?? [];
        unset($data['equipment_ids']);

        $recurring->update($data);
        $recurring->equipment()->sync($equipmentIds);

        return redirect()->route('fsmrecurring.recurring.show', $recurring->id)
            ->with('success', 'Recurring schedule updated.');
    }

    public function destroy(int $id)
    {
        FSMRecurring::where('company_id', $this->companyId())->findOrFail($id)->delete();
        return redirect()->route('fsmrecurring.recurring.index')
            ->with('success', 'Recurring schedule deleted.');
    }

    // ─── State actions ────────────────────────────────────────────────────────

    public function start(int $id)
    {
        $recurring = FSMRecurring::where('company_id', $this->companyId())->findOrFail($id);
        $recurring->actionStart();
        return redirect()->route('fsmrecurring.recurring.show', $id)
            ->with('success', 'Recurring schedule started. Orders generated.');
    }

    public function suspend(int $id)
    {
        $recurring = FSMRecurring::where('company_id', $this->companyId())->findOrFail($id);
        $recurring->actionSuspend();
        return redirect()->route('fsmrecurring.recurring.show', $id)
            ->with('success', 'Recurring schedule suspended.');
    }

    public function resume(int $id)
    {
        $recurring = FSMRecurring::where('company_id', $this->companyId())->findOrFail($id);
        $recurring->actionResume();
        return redirect()->route('fsmrecurring.recurring.show', $id)
            ->with('success', 'Recurring schedule resumed.');
    }

    public function close(int $id)
    {
        $recurring = FSMRecurring::where('company_id', $this->companyId())->findOrFail($id);
        $recurring->actionClose();
        return redirect()->route('fsmrecurring.recurring.show', $id)
            ->with('success', 'Recurring schedule closed.');
    }

    public function generate(int $id)
    {
        $recurring = FSMRecurring::where('company_id', $this->companyId())->findOrFail($id);
        $created = $recurring->generateOrders();
        return redirect()->route('fsmrecurring.recurring.show', $id)
            ->with('success', count($created) . ' order(s) generated.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function formData(array $extra = []): array
    {
        return array_merge([
            'recurring'          => null,
            'locations'          => FSMLocation::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get(),
            'teams'              => FSMTeam::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get(),
            'templates'          => FSMTemplate::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get(),
            'frequencySets'      => FSMFrequencySet::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get(),
            'recurringTemplates' => FSMRecurringTemplate::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get(),
            'equipment'          => FSMEquipment::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get(),
            'workers'            => \App\Models\User::where('company_id', $this->companyId())->orderBy('name')->get(),
        ], $extra);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'recurring_template_id' => 'nullable|integer|exists:fsm_recurring_templates,id',
            'location_id'           => 'nullable|integer|exists:fsm_locations,id',
            'description'           => 'nullable|string|max:65535',
            'frequency_set_id'      => 'nullable|integer|exists:fsm_frequency_sets,id',
            'scheduled_duration'    => 'nullable|numeric|min:0',
            'start_date'            => 'nullable|date',
            'end_date'              => 'nullable|date|after_or_equal:start_date',
            'max_orders'            => 'nullable|integer|min:0',
            'fsm_template_id'       => 'nullable|integer|exists:fsm_templates,id',
            'team_id'               => 'nullable|integer|exists:fsm_teams,id',
            'person_id'             => 'nullable|integer',
            'equipment_ids'         => 'nullable|array',
            'equipment_ids.*'       => 'integer|exists:fsm_equipment,id',
        ]);
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int) $user->company_id;
    }
}
