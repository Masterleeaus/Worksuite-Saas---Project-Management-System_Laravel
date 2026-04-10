<?php

namespace Modules\FSMCRM\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCRM\Models\FSMLead;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMTemplate;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $q = FSMLead::query()->with(['fsmLocation', 'serviceType']);

        if ($request->filled('stage')) {
            $q->where('stage', $request->string('stage')->toString());
        }

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('contact_name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $leads  = $q->orderByDesc('id')->paginate(50)->withQueryString();
        $stages = FSMLead::stages();
        $filter = $request->only(['stage', 'q']);

        return view('fsmcrm::leads.index', compact('leads', 'stages', 'filter'));
    }

    public function create()
    {
        $stages    = FSMLead::stages();
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $templates = FSMTemplate::where('active', true)->orderBy('name')->get();

        return view('fsmcrm::leads.create', compact('stages', 'locations', 'templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'contact_name'     => 'nullable|string|max:128',
            'email'            => 'nullable|email|max:128',
            'phone'            => 'nullable|string|max:64',
            'notes'            => 'nullable|string|max:65535',
            'stage'            => 'required|string|in:new,qualified,won,lost',
            'expected_revenue' => 'nullable|numeric|min:0',
            'close_date'       => 'nullable|date',
            'fsm_location_id'  => 'nullable|integer|exists:fsm_locations,id',
            'service_type_id'  => 'nullable|integer|exists:fsm_templates,id',
            'site_count'       => 'nullable|integer|min:1',
            'estimated_hours'  => 'nullable|numeric|min:0',
            'create_recurring' => 'nullable|boolean',
        ]);

        $data['create_recurring'] = $request->boolean('create_recurring');
        $data['site_count']       = $data['site_count'] ?? 1;

        FSMLead::create($data);

        return redirect()->route('fsmcrm.leads.index')
            ->with('success', 'Lead created.');
    }

    public function show(int $id)
    {
        $lead   = FSMLead::with(['fsmLocation', 'serviceType', 'orders.stage', 'orders.location'])->findOrFail($id);
        $orders = $lead->orders()->with(['stage', 'location'])->get();

        return view('fsmcrm::leads.show', compact('lead', 'orders'));
    }

    public function edit(int $id)
    {
        $lead      = FSMLead::findOrFail($id);
        $stages    = FSMLead::stages();
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $templates = FSMTemplate::where('active', true)->orderBy('name')->get();

        return view('fsmcrm::leads.edit', compact('lead', 'stages', 'locations', 'templates'));
    }

    public function update(Request $request, int $id)
    {
        $lead = FSMLead::findOrFail($id);
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'contact_name'     => 'nullable|string|max:128',
            'email'            => 'nullable|email|max:128',
            'phone'            => 'nullable|string|max:64',
            'notes'            => 'nullable|string|max:65535',
            'stage'            => 'required|string|in:new,qualified,won,lost',
            'expected_revenue' => 'nullable|numeric|min:0',
            'close_date'       => 'nullable|date',
            'fsm_location_id'  => 'nullable|integer|exists:fsm_locations,id',
            'service_type_id'  => 'nullable|integer|exists:fsm_templates,id',
            'site_count'       => 'nullable|integer|min:1',
            'estimated_hours'  => 'nullable|numeric|min:0',
            'create_recurring' => 'nullable|boolean',
        ]);

        $data['create_recurring'] = $request->boolean('create_recurring');

        $lead->update($data);

        return redirect()->route('fsmcrm.leads.show', $lead->id)
            ->with('success', 'Lead updated.');
    }

    public function destroy(int $id)
    {
        FSMLead::findOrFail($id)->delete();

        return redirect()->route('fsmcrm.leads.index')
            ->with('success', 'Lead deleted.');
    }
}
