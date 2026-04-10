<?php

namespace Modules\FSMVehicle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMVehicle\Models\FSMVehicle;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $q = FSMVehicle::query()->with('driver');

        if ($request->filled('q')) {
            $term = trim((string) $request->get('q'));
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('license_plate', 'like', "%{$term}%")
                    ->orWhere('make', 'like', "%{$term}%")
                    ->orWhere('model', 'like', "%{$term}%");
            });
        }

        if ($request->filled('active')) {
            $q->where('active', (bool) $request->get('active'));
        }

        $vehicles = $q->orderBy('name')->paginate(50)->withQueryString();
        $filter   = $request->only(['q', 'active']);

        $buffer        = (int) config('fsmvehicle.service_alert_buffer_km', 500);
        $serviceAlerts = FSMVehicle::where('active', true)
            ->whereNotNull('next_service_mileage')
            ->whereRaw('current_mileage >= (next_service_mileage - ?)', [$buffer])
            ->with('driver')
            ->get();

        return view('fsmvehicle::vehicles.index', compact('vehicles', 'filter', 'serviceAlerts'));
    }

    public function create()
    {
        $vehicle = null;
        $users   = \App\Models\User::orderBy('name')->get();

        return view('fsmvehicle::vehicles.create', compact('vehicle', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:128',
            'license_plate'        => 'nullable|string|max:32',
            'make'                 => 'nullable|string|max:64',
            'model'                => 'nullable|string|max:64',
            'year'                 => 'nullable|integer|min:1900|max:2100',
            'vin'                  => 'nullable|string|max:64',
            'person_id'            => 'nullable|integer|exists:users,id',
            'current_mileage'      => 'nullable|integer|min:0',
            'last_service_date'    => 'nullable|date',
            'next_service_mileage' => 'nullable|integer|min:0',
            'notes'                => 'nullable|string|max:65535',
            'active'               => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active', true);

        $vehicle = FSMVehicle::create($data);

        return redirect()->route('fsmvehicle.vehicles.show', $vehicle->id)
            ->with('success', 'Vehicle created successfully.');
    }

    public function show(int $id)
    {
        $vehicle = FSMVehicle::with(['driver', 'mileageLogs.order', 'mileageLogs.logger', 'orders.stage'])->findOrFail($id);

        return view('fsmvehicle::vehicles.show', compact('vehicle'));
    }

    public function edit(int $id)
    {
        $vehicle = FSMVehicle::findOrFail($id);
        $users   = \App\Models\User::orderBy('name')->get();

        return view('fsmvehicle::vehicles.edit', compact('vehicle', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $vehicle = FSMVehicle::findOrFail($id);

        $data = $request->validate([
            'name'                 => 'required|string|max:128',
            'license_plate'        => 'nullable|string|max:32',
            'make'                 => 'nullable|string|max:64',
            'model'                => 'nullable|string|max:64',
            'year'                 => 'nullable|integer|min:1900|max:2100',
            'vin'                  => 'nullable|string|max:64',
            'person_id'            => 'nullable|integer|exists:users,id',
            'current_mileage'      => 'nullable|integer|min:0',
            'last_service_date'    => 'nullable|date',
            'next_service_mileage' => 'nullable|integer|min:0',
            'notes'                => 'nullable|string|max:65535',
            'active'               => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active', true);

        $vehicle->update($data);

        return redirect()->route('fsmvehicle.vehicles.show', $vehicle->id)
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(int $id)
    {
        $vehicle = FSMVehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->route('fsmvehicle.vehicles.index')
            ->with('success', 'Vehicle deleted.');
    }

    public function report(Request $request)
    {
        $vehicles = FSMVehicle::where('active', true)->orderBy('name')->get();

        $from = $request->input('from') ? \Carbon\Carbon::parse($request->input('from'))->startOfDay() : null;
        $to   = $request->input('to')   ? \Carbon\Carbon::parse($request->input('to'))->endOfDay()     : null;

        $vehicleId = $request->filled('vehicle_id') ? (int) $request->input('vehicle_id') : null;

        $logsQuery = \Modules\FSMVehicle\Models\FSMVehicleMileageLog::query()
            ->with(['vehicle', 'order'])
            ->when($vehicleId, fn ($q) => $q->where('vehicle_id', $vehicleId))
            ->when($from,      fn ($q) => $q->where('log_date', '>=', $from))
            ->when($to,        fn ($q) => $q->where('log_date', '<=', $to))
            ->orderBy('log_date');

        $logs = $logsQuery->get();

        $totalKm = $logs->sum('km_driven');

        return view('fsmvehicle::vehicles.report', compact('vehicles', 'logs', 'totalKm', 'from', 'to', 'vehicleId'));
    }
}
