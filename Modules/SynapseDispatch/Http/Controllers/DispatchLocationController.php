<?php

namespace Modules\SynapseDispatch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SynapseDispatch\Models\DispatchLocation;

class DispatchLocationController extends Controller
{
    public function index(Request $request)
    {
        $q = DispatchLocation::query();

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('location_code', 'like', "%{$term}%")
                    ->orWhere('address', 'like', "%{$term}%");
            });
        }

        $locations = $q->orderBy('location_code')->paginate(50)->withQueryString();
        $filter    = $request->only(['q']);

        return view('synapsedispatch::locations.index', compact('locations', 'filter'));
    }

    public function create()
    {
        return view('synapsedispatch::locations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:64|unique:dispatch_locations,location_code',
            'address'       => 'nullable|string|max:255',
            'geo_latitude'  => 'nullable|numeric|between:-90,90',
            'geo_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        DispatchLocation::create($data);

        return redirect()->route('synapsedispatch.locations.index')
            ->with('success', 'Location created.');
    }

    public function show(DispatchLocation $location)
    {
        $location->load(['workers', 'jobs']);
        return view('synapsedispatch::locations.show', compact('location'));
    }

    public function edit(DispatchLocation $location)
    {
        return view('synapsedispatch::locations.edit', compact('location'));
    }

    public function update(Request $request, DispatchLocation $location)
    {
        $data = $request->validate([
            'address'       => 'nullable|string|max:255',
            'geo_latitude'  => 'nullable|numeric|between:-90,90',
            'geo_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $location->update($data);

        return redirect()->route('synapsedispatch.locations.index')
            ->with('success', "Location {$location->location_code} updated.");
    }

    public function destroy(DispatchLocation $location)
    {
        $code = $location->location_code;
        $location->delete();
        return redirect()->route('synapsedispatch.locations.index')
            ->with('success', "Location {$code} deleted.");
    }
}
