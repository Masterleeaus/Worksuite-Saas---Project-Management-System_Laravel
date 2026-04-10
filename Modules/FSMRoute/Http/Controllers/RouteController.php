<?php

namespace Modules\FSMRoute\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRoute\Models\FSMRoute;
use Modules\FSMRoute\Models\FSMRouteDay;
use Modules\FSMCore\Models\FSMLocation;
use App\Models\User;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only(['q']);
        $routes = FSMRoute::with(['person', 'days', 'locations'])
            ->when($filter['q'] ?? null, fn($q, $v) => $q->where('name', 'like', "%$v%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('fsmroute::routes.index', compact('routes', 'filter'));
    }

    public function create()
    {
        $days      = FSMRouteDay::orderBy('day_index')->get();
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $users     = User::orderBy('name')->get();

        return view('fsmroute::routes.create', compact('days', 'locations', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:256',
            'person_id'     => 'nullable|integer',
            'max_order'     => 'nullable|integer|min:0',
            'active'        => 'nullable|boolean',
            'day_ids'       => 'nullable|array',
            'day_ids.*'     => 'integer|exists:fsm_route_days,id',
            'location_ids'  => 'nullable|array',
            'location_ids.*'=> 'integer|exists:fsm_locations,id',
        ]);

        $route = FSMRoute::create([
            'name'      => $data['name'],
            'person_id' => $data['person_id'] ?? null,
            'max_order' => $data['max_order'] ?? 0,
            'active'    => $request->boolean('active', true),
        ]);

        $route->days()->sync($data['day_ids'] ?? []);
        $route->locations()->sync($data['location_ids'] ?? []);

        return redirect()->route('fsmroute.routes.index')
            ->with('success', 'Route created successfully.');
    }

    public function edit(int $id)
    {
        $route     = FSMRoute::with(['days', 'locations'])->findOrFail($id);
        $days      = FSMRouteDay::orderBy('day_index')->get();
        $locations = FSMLocation::where('active', true)->orderBy('name')->get();
        $users     = User::orderBy('name')->get();

        return view('fsmroute::routes.edit', compact('route', 'days', 'locations', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $route = FSMRoute::findOrFail($id);

        $data = $request->validate([
            'name'          => 'required|string|max:256',
            'person_id'     => 'nullable|integer',
            'max_order'     => 'nullable|integer|min:0',
            'active'        => 'nullable|boolean',
            'day_ids'       => 'nullable|array',
            'day_ids.*'     => 'integer|exists:fsm_route_days,id',
            'location_ids'  => 'nullable|array',
            'location_ids.*'=> 'integer|exists:fsm_locations,id',
        ]);

        $route->update([
            'name'      => $data['name'],
            'person_id' => $data['person_id'] ?? null,
            'max_order' => $data['max_order'] ?? 0,
            'active'    => $request->boolean('active', true),
        ]);

        $route->days()->sync($data['day_ids'] ?? []);
        $route->locations()->sync($data['location_ids'] ?? []);

        return redirect()->route('fsmroute.routes.index')
            ->with('success', 'Route updated successfully.');
    }

    public function destroy(int $id)
    {
        FSMRoute::findOrFail($id)->delete();

        return redirect()->route('fsmroute.routes.index')
            ->with('success', 'Route deleted.');
    }
}
