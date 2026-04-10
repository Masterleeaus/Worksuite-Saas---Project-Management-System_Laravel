<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMTerritory;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $q = FSMLocation::query()->with('territory');

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('street', 'like', "%{$term}%");
            });
        }

        $locations = $q->orderBy('name')->paginate(50)->withQueryString();
        $filter = $request->only(['q']);

        return view('fsmcore::locations.index', compact('locations', 'filter'));
    }

    public function create()
    {
        $territories = FSMTerritory::where('active', true)->orderBy('name')->get();
        return view('fsmcore::locations.create', compact('territories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:256',
            'partner_id'   => 'nullable|integer',
            'territory_id' => 'nullable|integer|exists:fsm_territories,id',
            'street'       => 'nullable|string|max:256',
            'city'         => 'nullable|string|max:128',
            'state'        => 'nullable|string|max:128',
            'zip'          => 'nullable|string|max:32',
            'country'      => 'nullable|string|max:128',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'notes'        => 'nullable|string|max:65535',
            'active'       => 'nullable|boolean',
        ]);

        $location = FSMLocation::create($data);

        return redirect()->route('fsmcore.locations.show', $location->id)
            ->with('success', 'Location created.');
    }

    public function show(int $id)
    {
        $location = FSMLocation::with(['territory', 'orders', 'equipment'])->findOrFail($id);
        return view('fsmcore::locations.show', compact('location'));
    }

    public function edit(int $id)
    {
        $location = FSMLocation::findOrFail($id);
        $territories = FSMTerritory::where('active', true)->orderBy('name')->get();
        return view('fsmcore::locations.edit', compact('location', 'territories'));
    }

    public function update(Request $request, int $id)
    {
        $location = FSMLocation::findOrFail($id);

        $data = $request->validate([
            'name'         => 'required|string|max:256',
            'partner_id'   => 'nullable|integer',
            'territory_id' => 'nullable|integer|exists:fsm_territories,id',
            'street'       => 'nullable|string|max:256',
            'city'         => 'nullable|string|max:128',
            'state'        => 'nullable|string|max:128',
            'zip'          => 'nullable|string|max:32',
            'country'      => 'nullable|string|max:128',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'notes'        => 'nullable|string|max:65535',
            'active'       => 'nullable|boolean',
        ]);

        $location->update($data);

        return redirect()->route('fsmcore.locations.show', $location->id)
            ->with('success', 'Location updated.');
    }

    public function destroy(int $id)
    {
        $location = FSMLocation::findOrFail($id);
        $location->delete();

        return redirect()->route('fsmcore.locations.index')
            ->with('success', 'Location deleted.');
    }
}
