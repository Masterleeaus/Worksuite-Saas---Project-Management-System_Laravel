<?php

namespace Modules\FSMRecurring\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRecurring\Models\FSMFrequency;
use Modules\FSMRecurring\Models\FSMFrequencySet;

class FrequencySetController extends Controller
{
    public function index()
    {
        $sets = FSMFrequencySet::where('company_id', $this->companyId())
            ->withCount('frequencies')
            ->orderBy('name')
            ->paginate(50);
        return view('fsmrecurring::frequency-sets.index', compact('sets'));
    }

    public function create()
    {
        $frequencies = FSMFrequency::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get();
        return view('fsmrecurring::frequency-sets.create', [
            'set'         => null,
            'frequencies' => $frequencies,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $frequencyIds = $data['frequency_ids'] ?? [];
        unset($data['frequency_ids']);
        $data['company_id'] = $this->companyId();

        $set = FSMFrequencySet::create($data);
        $set->frequencies()->sync(
            FSMFrequency::where('company_id', $this->companyId())->whereIn('id', $frequencyIds)->pluck('id')->all()
        );

        return redirect()->route('fsmrecurring.frequency-sets.index')
            ->with('success', 'Frequency set created.');
    }

    public function edit(int $id)
    {
        $set = FSMFrequencySet::with('frequencies')->where('company_id', $this->companyId())->findOrFail($id);
        $frequencies = FSMFrequency::where('company_id', $this->companyId())->where('active', true)->orderBy('name')->get();
        return view('fsmrecurring::frequency-sets.edit', compact('set', 'frequencies'));
    }

    public function update(Request $request, int $id)
    {
        $set = FSMFrequencySet::where('company_id', $this->companyId())->findOrFail($id);
        $data = $this->validated($request);
        $frequencyIds = $data['frequency_ids'] ?? [];
        unset($data['frequency_ids']);

        $set->update($data);
        $set->frequencies()->sync(
            FSMFrequency::where('company_id', $this->companyId())->whereIn('id', $frequencyIds)->pluck('id')->all()
        );

        return redirect()->route('fsmrecurring.frequency-sets.index')
            ->with('success', 'Frequency set updated.');
    }

    public function destroy(int $id)
    {
        FSMFrequencySet::where('company_id', $this->companyId())->findOrFail($id)->delete();
        return redirect()->route('fsmrecurring.frequency-sets.index')
            ->with('success', 'Frequency set deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'          => 'required|string|max:255',
            'active'        => 'nullable|boolean',
            'schedule_days' => 'required|integer|min:1',
            'buffer_early'  => 'nullable|integer|min:0',
            'buffer_late'   => 'nullable|integer|min:0',
            'frequency_ids'   => 'nullable|array',
            'frequency_ids.*' => 'integer|exists:fsm_frequencies,id',
        ]);
    }

    private function companyId(): int
    {
        $user = auth()->user();
        abort_if(!$user || !$user->company_id, 403);

        return (int) $user->company_id;
    }
}
