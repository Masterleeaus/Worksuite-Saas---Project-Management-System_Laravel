<?php

namespace Modules\FSMRoute\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMRoute\Models\FSMWorkerLocationPing;
use Modules\FSMRoute\Services\ETANotificationService;

class WorkerStatusController extends Controller
{
    public function __construct(private readonly ETANotificationService $eta) {}

    /**
     * Mark a worker as en route to a job and send an ETA notification to the client.
     */
    public function enRoute(int $id)
    {
        $order = FSMOrder::with('location', 'person')->findOrFail($id);

        $this->eta->notifyEnRoute($order);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true, 'status' => 'en_route']);
        }

        return redirect()->back()->with('success', "Worker marked en route for {$order->name}. Client notification sent.");
    }

    /**
     * Check in: record the actual job start time.
     */
    public function checkIn(int $id)
    {
        $order = FSMOrder::with('location', 'person')->findOrFail($id);

        $order->update(['date_start' => now()]);

        $this->eta->notifyCheckIn($order);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true, 'status' => 'checked_in', 'date_start' => $order->date_start]);
        }

        return redirect()->back()->with('success', "Check-in recorded for {$order->name}.");
    }

    /**
     * Check out: record the actual job completion time.
     */
    public function checkOut(int $id)
    {
        $order = FSMOrder::with('location', 'person')->findOrFail($id);

        $order->update(['date_end' => now()]);

        $this->eta->notifyComplete($order);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true, 'status' => 'checked_out', 'date_end' => $order->date_end]);
        }

        return redirect()->back()->with('success', "Job {$order->name} marked complete.");
    }

    /**
     * Receive a GPS location ping from a field worker.
     * Accepts JSON: { "latitude": float, "longitude": float }
     */
    public function pingLocation(Request $request)
    {
        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $userId = Auth::id();

        FSMWorkerLocationPing::create([
            'company_id' => Auth::user()->company_id ?? null,
            'person_id'  => $userId,
            'latitude'   => $data['latitude'],
            'longitude'  => $data['longitude'],
            'pinged_at'  => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}
