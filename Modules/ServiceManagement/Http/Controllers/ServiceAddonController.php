<?php

namespace Modules\ServiceManagement\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\ServiceAddon;

class ServiceAddonController extends Controller
{
    /** Predefined add-on types for a cleaning business */
    public const PREDEFINED_ADDONS = [
        'inside_fridge'    => 'Inside Fridge',
        'inside_oven'      => 'Inside Oven',
        'inside_cabinets'  => 'Inside Cabinets',
        'laundry'          => 'Laundry',
        'windows'          => 'Windows',
        'balcony'          => 'Balcony',
    ];

    public function index(Request $request): View
    {
        $addons = ServiceAddon::with('service')
            ->when($request->filled('service_id'), fn ($q) => $q->where('service_id', $request->service_id))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $services = Service::withoutGlobalScopes()->where('is_active', 1)->get(['id', 'name']);

        return view('servicemanagement::addons.index', compact('addons', 'services'));
    }

    public function create(): View
    {
        $services        = Service::withoutGlobalScopes()->where('is_active', 1)->get(['id', 'name']);
        $predefinedAddons = self::PREDEFINED_ADDONS;

        return view('servicemanagement::addons.create', compact('services', 'predefinedAddons'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:191',
            'price'          => 'required|numeric|min:0',
            'duration_extra' => 'nullable|integer|min:0',
            'service_id'     => 'nullable|uuid',
            'is_active'      => 'nullable|boolean',
        ]);

        $addon = new ServiceAddon($validated);
        $addon->is_active = $request->boolean('is_active', true);
        $addon->save();

        session()->flash('success', __('app.addonCreatedSuccessfully', [], 'Add-on created successfully.'));

        return redirect()->route('services.addons.index');
    }

    public function edit(string $id): View
    {
        $addon    = ServiceAddon::findOrFail($id);
        $services = Service::withoutGlobalScopes()->where('is_active', 1)->get(['id', 'name']);

        return view('servicemanagement::addons.edit', compact('addon', 'services'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $addon = ServiceAddon::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:191',
            'price'          => 'required|numeric|min:0',
            'duration_extra' => 'nullable|integer|min:0',
            'service_id'     => 'nullable|uuid',
            'is_active'      => 'nullable|boolean',
        ]);

        $addon->fill($validated);
        $addon->is_active = $request->boolean('is_active', $addon->is_active);
        $addon->save();

        session()->flash('success', __('app.addonUpdatedSuccessfully', [], 'Add-on updated successfully.'));

        return redirect()->route('services.addons.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        ServiceAddon::findOrFail($id)->delete();

        session()->flash('success', __('app.addonDeletedSuccessfully', [], 'Add-on deleted successfully.'));

        return redirect()->route('services.addons.index');
    }
}
