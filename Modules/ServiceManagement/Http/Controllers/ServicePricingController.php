<?php

namespace Modules\ServiceManagement\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\ServicePricingRule;
use Modules\ZoneManagement\Entities\Zone;

class ServicePricingController extends Controller
{
    public function index(Request $request): View
    {
        $rules = ServicePricingRule::with('service')
            ->when($request->filled('service_id'), fn ($q) => $q->where('service_id', $request->service_id))
            ->when($request->filled('zone_id'), fn ($q) => $q->where('zone_id', $request->zone_id))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $services = Service::withoutGlobalScopes()->where('is_active', 1)->get(['id', 'name']);
        $zones     = $this->getZones();

        return view('servicemanagement::pricing.index', compact('rules', 'services', 'zones'));
    }

    public function create(): View
    {
        $services = Service::withoutGlobalScopes()->where('is_active', 1)->get(['id', 'name']);
        $zones     = $this->getZones();

        return view('servicemanagement::pricing.create', compact('services', 'zones'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id'          => 'required|uuid',
            'zone_id'             => 'nullable',
            'label'               => 'nullable|string|max:191',
            'base_price_override' => 'nullable|numeric|min:0',
            'per_bedroom_price'   => 'nullable|numeric|min:0',
            'per_bathroom_price'  => 'nullable|numeric|min:0',
            'min_price'           => 'nullable|numeric|min:0',
            'is_active'           => 'nullable|boolean',
        ]);

        $rule = new ServicePricingRule($validated);
        $rule->is_active = $request->boolean('is_active', true);
        $rule->save();

        session()->flash('success', __('app.pricingRuleCreatedSuccessfully', [], 'Pricing rule created successfully.'));

        return redirect()->route('services.pricing.index');
    }

    public function edit(string $id): View
    {
        $rule     = ServicePricingRule::findOrFail($id);
        $services = Service::withoutGlobalScopes()->where('is_active', 1)->get(['id', 'name']);
        $zones     = $this->getZones();

        return view('servicemanagement::pricing.edit', compact('rule', 'services', 'zones'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $rule = ServicePricingRule::findOrFail($id);

        $validated = $request->validate([
            'service_id'          => 'required|uuid',
            'zone_id'             => 'nullable',
            'label'               => 'nullable|string|max:191',
            'base_price_override' => 'nullable|numeric|min:0',
            'per_bedroom_price'   => 'nullable|numeric|min:0',
            'per_bathroom_price'  => 'nullable|numeric|min:0',
            'min_price'           => 'nullable|numeric|min:0',
            'is_active'           => 'nullable|boolean',
        ]);

        $rule->fill($validated);
        $rule->is_active = $request->boolean('is_active', $rule->is_active);
        $rule->save();

        session()->flash('success', __('app.pricingRuleUpdatedSuccessfully', [], 'Pricing rule updated successfully.'));

        return redirect()->route('services.pricing.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        ServicePricingRule::findOrFail($id)->delete();

        session()->flash('success', __('app.pricingRuleDeletedSuccessfully', [], 'Pricing rule deleted successfully.'));

        return redirect()->route('services.pricing.index');
    }

    private function getZones()
    {
        if (class_exists(\Modules\ZoneManagement\Entities\Zone::class)) {
            return Zone::where('is_active', 1)->get();
        }
        return collect();
    }
}
