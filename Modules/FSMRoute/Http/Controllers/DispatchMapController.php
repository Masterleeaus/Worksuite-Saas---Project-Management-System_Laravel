<?php

namespace Modules\FSMRoute\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMRoute\Models\FSMDayRoute;
use Modules\FSMRoute\Models\FSMWorkerLocationPing;
use Carbon\Carbon;

class DispatchMapController extends Controller
{
    /**
     * Show the live dispatcher map for a given date (defaults to today).
     */
    public function index(Request $request)
    {
        $date = $request->date
            ? Carbon::parse($request->date)
            : Carbon::today();

        $dayRoutes = FSMDayRoute::with(['person', 'orders.location'])
            ->whereDate('date', $date)
            ->orderBy('id')
            ->get();

        return view('fsmroute::dispatch_map.index', compact('date', 'dayRoutes'));
    }

    /**
     * JSON API: return the latest location ping for each active worker today.
     * Used by the map view to auto-refresh worker positions.
     */
    public function locations(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        // Collect person IDs that have day routes today
        $workerIds = FSMDayRoute::whereDate('date', $date)->pluck('person_id')->filter()->unique();

        // Latest ping per worker
        $pings = FSMWorkerLocationPing::with('person:id,name')
            ->whereIn('person_id', $workerIds)
            ->latestPerWorker()
            ->get();

        $workers = $pings->map(fn($ping) => [
            'person_id' => $ping->person_id,
            'name'      => $ping->person?->name ?? 'Worker',
            'latitude'  => $ping->latitude,
            'longitude' => $ping->longitude,
            'pinged_at' => $ping->pinged_at?->diffForHumans(),
        ]);

        // Job locations for today's routes
        $jobs = [];
        foreach ($dayRoutes = FSMDayRoute::with(['orders.location', 'person'])
            ->whereDate('date', $date)
            ->get() as $dr) {
            foreach ($dr->orders as $order) {
                $loc = $order->location;
                if ($loc && $loc->latitude && $loc->longitude) {
                    $jobs[] = [
                        'order_id'  => $order->id,
                        'order_name'=> $order->name,
                        'worker'    => $dr->person?->name ?? 'Unassigned',
                        'sequence'  => $order->route_sequence + 1,
                        'latitude'  => $loc->latitude,
                        'longitude' => $loc->longitude,
                        'location'  => $loc->name,
                        'checked_in' => (bool) $order->date_start,
                        'complete'   => (bool) $order->date_end,
                    ];
                }
            }
        }

        return response()->json(compact('workers', 'jobs'));
    }
}
