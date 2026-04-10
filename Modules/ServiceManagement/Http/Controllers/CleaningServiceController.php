<?php

namespace Modules\ServiceManagement\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\ServiceManagement\Entities\Service;
use Modules\CategoryManagement\Entities\Category;
use Modules\ZoneManagement\Entities\Zone;

class CleaningServiceController extends Controller
{
    /** Cleaning service types available in the system */
    public const SERVICE_TYPES = [
        'residential_clean'   => 'Residential Clean',
        'commercial_clean'    => 'Commercial Clean',
        'deep_clean'          => 'Deep Clean',
        'move_in_out'         => 'Move-In/Move-Out',
        'airbnb_turnover'     => 'Airbnb Turnover',
        'spring_clean'        => 'Spring Clean',
        'end_of_lease'        => 'End-of-Lease',
    ];

    /** Frequency options */
    public const FREQUENCY_OPTIONS = [
        'one_off'     => 'One-off',
        'weekly'      => 'Weekly',
        'fortnightly' => 'Fortnightly',
        'monthly'     => 'Monthly',
    ];

    public function index(Request $request): View
    {
        $services = Service::withoutGlobalScopes()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status))
            ->when($request->filled('frequency'), fn ($q) => $q->where('frequency', $request->frequency))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('servicemanagement::cleaning.index', compact('services'));
    }

    public function create(): View
    {
        $categories = $this->getCategories();
        $zones       = $this->getZones();

        return view('servicemanagement::cleaning.create', [
            'categories'       => $categories,
            'zones'            => $zones,
            'serviceTypes'     => self::SERVICE_TYPES,
            'frequencyOptions' => self::FREQUENCY_OPTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:191',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'category_id'       => 'nullable|uuid',
            'duration_minutes'  => 'nullable|integer|min:0',
            'base_price'        => 'nullable|numeric|min:0',
            'frequency'         => 'nullable|string|in:' . implode(',', array_keys(self::FREQUENCY_OPTIONS)),
            'eco_friendly'      => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
            'zone_id'           => 'nullable',
            'thumbnail'         => 'nullable|image|max:2048',
        ]);

        $service = new Service();
        $service->fill($validated);
        $service->is_active    = $request->boolean('is_active', true);
        $service->eco_friendly = $request->boolean('eco_friendly', false);

        if ($request->hasFile('thumbnail')) {
            $service->thumbnail = $request->file('thumbnail')->store('service', 'public');
        }

        $service->save();

        session()->flash('success', __('app.serviceCreatedSuccessfully', [], 'Service created successfully.'));

        return redirect()->route('services.index');
    }

    public function show(string $id): View
    {
        $service = Service::withoutGlobalScopes()->with(['addons', 'pricingRules'])->findOrFail($id);

        return view('servicemanagement::cleaning.show', compact('service'));
    }

    public function edit(string $id): View
    {
        $service     = Service::withoutGlobalScopes()->findOrFail($id);
        $categories  = $this->getCategories();
        $zones        = $this->getZones();

        return view('servicemanagement::cleaning.edit', [
            'service'          => $service,
            'categories'       => $categories,
            'zones'            => $zones,
            'serviceTypes'     => self::SERVICE_TYPES,
            'frequencyOptions' => self::FREQUENCY_OPTIONS,
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $service = Service::withoutGlobalScopes()->findOrFail($id);

        $validated = $request->validate([
            'name'              => 'required|string|max:191',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'category_id'       => 'nullable|uuid',
            'duration_minutes'  => 'nullable|integer|min:0',
            'base_price'        => 'nullable|numeric|min:0',
            'frequency'         => 'nullable|string|in:' . implode(',', array_keys(self::FREQUENCY_OPTIONS)),
            'eco_friendly'      => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
            'zone_id'           => 'nullable',
            'thumbnail'         => 'nullable|image|max:2048',
        ]);

        $service->fill($validated);
        $service->is_active    = $request->boolean('is_active', $service->is_active);
        $service->eco_friendly = $request->boolean('eco_friendly', $service->eco_friendly ?? false);

        if ($request->hasFile('thumbnail')) {
            $service->thumbnail = $request->file('thumbnail')->store('service', 'public');
        }

        $service->save();

        session()->flash('success', __('app.serviceUpdatedSuccessfully', [], 'Service updated successfully.'));

        return redirect()->route('services.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        $service = Service::withoutGlobalScopes()->findOrFail($id);
        $service->delete();

        session()->flash('success', __('app.serviceDeletedSuccessfully', [], 'Service deleted successfully.'));

        return redirect()->route('services.index');
    }

    public function toggleActive(string $id): RedirectResponse
    {
        $service            = Service::withoutGlobalScopes()->findOrFail($id);
        $service->is_active = ! $service->is_active;
        $service->save();

        return back();
    }

    private function getCategories()
    {
        if (class_exists(\Modules\CategoryManagement\Entities\Category::class)) {
            return Category::where('is_active', 1)->get();
        }
        return collect();
    }

    private function getZones()
    {
        if (class_exists(\Modules\ZoneManagement\Entities\Zone::class)) {
            return Zone::where('is_active', 1)->get();
        }
        return collect();
    }
}
