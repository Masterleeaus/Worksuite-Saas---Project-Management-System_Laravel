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
        $sets = FSMFrequencySet::withCount('frequencies')->orderBy('name')->paginate(50);
        return view('fsmrecurring::frequency-sets.index', compact('sets'));
    }

    public function create()
    {
        $frequencies = FSMFrequency::where('active', true)->orderBy('name')->get();
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

        $set = FSMFrequencySet::create($data);
        $set->frequencies()->sync($frequencyIds);

        return redirect()->route('fsmrecurring.frequency-sets.index')
            ->with('success', 'Frequency set created.');
    }

    public function edit(int $id)
    {
        $set = FSMFrequencySet::with('frequencies')->findOrFail($id);
        $frequencies = FSMFrequency::where('active', true)->orderBy('name')->get();
        return view('fsmrecurring::frequency-sets.edit', compact('set', 'frequencies'));
    }

    public function update(Request $request, int $id)
    {
        $set = FSMFrequencySet::findOrFail($id);
        $data = $this->validated($request);
        $frequencyIds = $data['frequency_ids'] ?? [];
        unset($data['frequency_ids']);

        $set->update($data);
        $set->frequencies()->sync($frequencyIds);

        return redirect()->route('fsmrecurring.frequency-sets.index')
            ->with('success', 'Frequency set updated.');
    }

    public function destroy(int $id)
    {
        FSMFrequencySet::findOrFail($id)->delete();
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
}
