<?php

namespace Modules\FSMRoute\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\FSMRoute\Models\FSMDayRoute;
use Modules\FSMRoute\Models\FSMRoute;
use Modules\FSMCore\Models\FSMOrder;
use App\Models\User;
use Carbon\Carbon;

class DayRouteController extends Controller
{
    public function index(Request $request)
    {
        $filter     = $request->only(['date', 'person_id']);
        $dayRoutes  = FSMDayRoute::with(['route', 'person'])
            ->when($filter['date'] ?? null, fn($q, $v) => $q->whereDate('date', $v))
            ->when($filter['person_id'] ?? null, fn($q, $v) => $q->where('person_id', $v))
            ->orderByDesc('date')
            ->paginate(20)
            ->withQueryString();

        $users = User::orderBy('name')->get();

        return view('fsmroute::day_routes.index', compact('dayRoutes', 'filter', 'users'));
    }

    public function board(Request $request)
    {
        $date = $request->date
            ? Carbon::parse($request->date)
            : Carbon::today();

        $dayRoutes = FSMDayRoute::with(['person', 'orders.location'])
            ->whereDate('date', $date)
            ->orderBy('id')
            ->get();

        $users = User::orderBy('name')->get();

        return view('fsmroute::day_routes.board', compact('date', 'dayRoutes', 'users'));
    }

    public function create()
    {
        $routes  = FSMRoute::where('active', true)->orderBy('name')->get();
        $users   = User::orderBy('name')->get();
        $orders  = FSMOrder::whereNull('dayroute_id')->orderBy('name')->get();

        return view('fsmroute::day_routes.create', compact('routes', 'users', 'orders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'route_id'           => 'nullable|integer|exists:fsm_routes,id',
            'date'               => 'required|date',
            'person_id'          => 'nullable|integer',
            'state'              => 'nullable|in:draft,confirmed,done',
            'date_start_planned' => 'nullable|date',
            'work_time'          => 'nullable|numeric|min:0',
            'max_allow_time'     => 'nullable|numeric|min:0',
            'order_ids'          => 'nullable|array',
            'order_ids.*'        => 'integer|exists:fsm_orders,id',
        ]);

        $route = $data['route_id'] ? FSMRoute::find($data['route_id']) : null;
        $date  = Carbon::parse($data['date'])->format('Y-m-d');
        $name  = ($route ? $route->name : 'Day Route') . ' – ' . $date;

        $dayRoute = FSMDayRoute::create([
            'name'               => $name,
            'route_id'           => $data['route_id'] ?? null,
            'date'               => $date,
            'person_id'          => $data['person_id'] ?? null,
            'state'              => $data['state'] ?? 'draft',
            'date_start_planned' => $data['date_start_planned'] ?? null,
            'work_time'          => $data['work_time'] ?? 8.0,
            'max_allow_time'     => $data['max_allow_time'] ?? 10.0,
        ]);

        foreach ($data['order_ids'] ?? [] as $seq => $orderId) {
            DB::table('fsm_orders')
                ->where('id', $orderId)
                ->update(['dayroute_id' => $dayRoute->id, 'route_sequence' => $seq]);
        }

        return redirect()->route('fsmroute.day_routes.index')
            ->with('success', 'Day route created successfully.');
    }

    public function show(int $id)
    {
        $dayRoute = FSMDayRoute::with(['route', 'person', 'orders.location'])->findOrFail($id);

        return view('fsmroute::day_routes.show', compact('dayRoute'));
    }

    public function edit(int $id)
    {
        $dayRoute = FSMDayRoute::with(['orders'])->findOrFail($id);
        $routes   = FSMRoute::where('active', true)->orderBy('name')->get();
        $users    = User::orderBy('name')->get();
        $orders   = FSMOrder::where(function ($q) use ($id) {
            $q->whereNull('dayroute_id')->orWhere('dayroute_id', $id);
        })->orderBy('name')->get();

        return view('fsmroute::day_routes.edit', compact('dayRoute', 'routes', 'users', 'orders'));
    }

    public function update(Request $request, int $id)
    {
        $dayRoute = FSMDayRoute::findOrFail($id);

        $data = $request->validate([
            'route_id'           => 'nullable|integer|exists:fsm_routes,id',
            'date'               => 'required|date',
            'person_id'          => 'nullable|integer',
            'state'              => 'nullable|in:draft,confirmed,done',
            'date_start_planned' => 'nullable|date',
            'work_time'          => 'nullable|numeric|min:0',
            'max_allow_time'     => 'nullable|numeric|min:0',
            'order_ids'          => 'nullable|array',
            'order_ids.*'        => 'integer|exists:fsm_orders,id',
        ]);

        $route = $data['route_id'] ? FSMRoute::find($data['route_id']) : null;
        $date  = Carbon::parse($data['date'])->format('Y-m-d');
        $name  = ($route ? $route->name : 'Day Route') . ' – ' . $date;

        $dayRoute->update([
            'name'               => $name,
            'route_id'           => $data['route_id'] ?? null,
            'date'               => $date,
            'person_id'          => $data['person_id'] ?? null,
            'state'              => $data['state'] ?? $dayRoute->state,
            'date_start_planned' => $data['date_start_planned'] ?? null,
            'work_time'          => $data['work_time'] ?? 8.0,
            'max_allow_time'     => $data['max_allow_time'] ?? 10.0,
        ]);

        // Clear previously assigned orders, then assign new ones
        DB::table('fsm_orders')
            ->where('dayroute_id', $id)
            ->update(['dayroute_id' => null, 'route_sequence' => 0]);

        foreach ($data['order_ids'] ?? [] as $seq => $orderId) {
            DB::table('fsm_orders')
                ->where('id', $orderId)
                ->update(['dayroute_id' => $dayRoute->id, 'route_sequence' => $seq]);
        }

        return redirect()->route('fsmroute.day_routes.index')
            ->with('success', 'Day route updated successfully.');
    }

    public function destroy(int $id)
    {
        $dayRoute = FSMDayRoute::findOrFail($id);

        DB::table('fsm_orders')
            ->where('dayroute_id', $id)
            ->update(['dayroute_id' => null, 'route_sequence' => 0]);

        $dayRoute->delete();

        return redirect()->route('fsmroute.day_routes.index')
            ->with('success', 'Day route deleted.');
    }

    public function print(int $id)
    {
        $dayRoute = FSMDayRoute::with(['route', 'person', 'orders.location'])->findOrFail($id);

        return view('fsmroute::day_routes.print', compact('dayRoute'));
    }

    public function reorder(Request $request, int $id)
    {
        $data = $request->validate([
            'order_ids'   => 'required|array',
            'order_ids.*' => 'integer|exists:fsm_orders,id',
        ]);

        foreach ($data['order_ids'] as $seq => $orderId) {
            DB::table('fsm_orders')
                ->where('id', $orderId)
                ->update(['route_sequence' => $seq]);
        }

        return response()->json(['ok' => true]);
    }
}
