<?php

namespace Modules\FSMRecurring\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRecurring\Models\FSMFrequency;

class FrequencyController extends Controller
{
    public function index()
    {
        $frequencies = FSMFrequency::orderBy('name')->paginate(50);
        return view('fsmrecurring::frequencies.index', compact('frequencies'));
    }

    public function create()
    {
        return view('fsmrecurring::frequencies.create', [
            'frequency'     => null,
            'intervalTypes' => FSMFrequency::$intervalTypes,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        FSMFrequency::create($data);
        return redirect()->route('fsmrecurring.frequencies.index')
            ->with('success', 'Frequency rule created.');
    }

    public function edit(int $id)
    {
        $frequency = FSMFrequency::findOrFail($id);
        return view('fsmrecurring::frequencies.edit', [
            'frequency'     => $frequency,
            'intervalTypes' => FSMFrequency::$intervalTypes,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $frequency = FSMFrequency::findOrFail($id);
        $data = $this->validated($request);
        $frequency->update($data);
        return redirect()->route('fsmrecurring.frequencies.index')
            ->with('success', 'Frequency rule updated.');
    }

    public function destroy(int $id)
    {
        FSMFrequency::findOrFail($id)->delete();
        return redirect()->route('fsmrecurring.frequencies.index')
            ->with('success', 'Frequency rule deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'          => 'required|string|max:255',
            'active'        => 'nullable|boolean',
            'interval'      => 'required|integer|min:1',
            'interval_type' => 'required|in:daily,weekly,monthly,yearly',
            'is_exclusive'  => 'nullable|boolean',
            // by-month-day
            'use_bymonthday' => 'nullable|boolean',
            'month_day'      => 'nullable|integer|min:1|max:31',
            // by-weekday
            'use_byweekday' => 'nullable|boolean',
            'weekday_mo'    => 'nullable|boolean',
            'weekday_tu'    => 'nullable|boolean',
            'weekday_we'    => 'nullable|boolean',
            'weekday_th'    => 'nullable|boolean',
            'weekday_fr'    => 'nullable|boolean',
            'weekday_sa'    => 'nullable|boolean',
            'weekday_su'    => 'nullable|boolean',
            // by-month
            'use_bymonth' => 'nullable|boolean',
            'month_jan'   => 'nullable|boolean',
            'month_feb'   => 'nullable|boolean',
            'month_mar'   => 'nullable|boolean',
            'month_apr'   => 'nullable|boolean',
            'month_may'   => 'nullable|boolean',
            'month_jun'   => 'nullable|boolean',
            'month_jul'   => 'nullable|boolean',
            'month_aug'   => 'nullable|boolean',
            'month_sep'   => 'nullable|boolean',
            'month_oct'   => 'nullable|boolean',
            'month_nov'   => 'nullable|boolean',
            'month_dec'   => 'nullable|boolean',
            // by-setpos
            'use_setpos' => 'nullable|boolean',
            'set_pos'    => 'nullable|integer|min:-366|max:366',
        ]);
    }
}
