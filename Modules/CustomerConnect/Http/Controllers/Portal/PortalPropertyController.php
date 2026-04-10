<?php

namespace Modules\CustomerConnect\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * PortalPropertyController — customer manages their own properties/addresses.
 * Integrates with ManagedPremises module when available.
 */
class PortalPropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List all properties belonging to the authenticated client.
     */
    public function index()
    {
        $user = Auth::user();
        $properties = collect();

        if (class_exists(\Modules\ManagedPremises\Entities\Property::class)
            && Schema::hasTable('properties')
        ) {
            try {
                $properties = \Modules\ManagedPremises\Entities\Property::query()
                    ->where('client_id', $user->id)
                    ->orderBy('name')
                    ->get();
            } catch (\Throwable) {
            }
        }

        return view('customerconnect::portal.properties.index', compact('properties'));
    }

    /**
     * Show the create property form.
     */
    public function create()
    {
        return view('customerconnect::portal.properties.create');
    }

    /**
     * Store a new property for the authenticated client.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'address'          => 'required|string|max:500',
            'property_type'    => 'nullable|string|max:100',
            'bedrooms'         => 'nullable|integer|min:0|max:50',
            'bathrooms'        => 'nullable|integer|min:0|max:50',
            'access_method'    => 'nullable|string|max:100',
            'special_instructions' => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();

        if (!class_exists(\Modules\ManagedPremises\Entities\Property::class)
            || !Schema::hasTable('properties')
        ) {
            return redirect()->route('customerconnect.portal.properties.index')
                ->with('error', 'Property management is not available at this time.');
        }

        try {
            \Modules\ManagedPremises\Entities\Property::create(array_merge(
                $request->only(['name', 'address', 'property_type', 'bedrooms', 'bathrooms', 'access_method', 'special_instructions']),
                [
                    'client_id'  => $user->id,
                    'company_id' => $user->company_id,
                ]
            ));
        } catch (\Throwable $e) {
            return redirect()->route('customerconnect.portal.properties.index')
                ->with('error', 'Could not save property. Please try again.');
        }

        return redirect()->route('customerconnect.portal.properties.index')
            ->with('success', 'Property added successfully.');
    }

    /**
     * Show the edit form for an existing property.
     */
    public function edit(int $id)
    {
        $user = Auth::user();

        if (!class_exists(\Modules\ManagedPremises\Entities\Property::class)
            || !Schema::hasTable('properties')
        ) {
            abort(404);
        }

        $property = \Modules\ManagedPremises\Entities\Property::query()
            ->where('client_id', $user->id)
            ->findOrFail($id);

        return view('customerconnect::portal.properties.edit', compact('property'));
    }

    /**
     * Update an existing property.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'address'          => 'required|string|max:500',
            'property_type'    => 'nullable|string|max:100',
            'bedrooms'         => 'nullable|integer|min:0|max:50',
            'bathrooms'        => 'nullable|integer|min:0|max:50',
            'access_method'    => 'nullable|string|max:100',
            'special_instructions' => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();

        if (!class_exists(\Modules\ManagedPremises\Entities\Property::class)
            || !Schema::hasTable('properties')
        ) {
            abort(404);
        }

        $property = \Modules\ManagedPremises\Entities\Property::query()
            ->where('client_id', $user->id)
            ->findOrFail($id);

        $property->update($request->only([
            'name', 'address', 'property_type', 'bedrooms',
            'bathrooms', 'access_method', 'special_instructions',
        ]));

        return redirect()->route('customerconnect.portal.properties.index')
            ->with('success', 'Property updated successfully.');
    }
}
