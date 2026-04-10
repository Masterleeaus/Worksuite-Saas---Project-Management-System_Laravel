<?php

namespace Modules\FSMVehicle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMVehicle\Models\FSMVehicle;
use Modules\FSMVehicle\Models\FSMVehicleMileageLog;
use Modules\FSMCore\Models\FSMOrder;

class MileageLogController extends Controller
{
    public function store(Request $request, int $vehicleId)
    {
        $vehicle = FSMVehicle::findOrFail($vehicleId);

        $data = $request->validate([
            'fsm_order_id'   => 'nullable|integer|exists:fsm_orders,id',
            'odometer_start' => 'required|integer|min:0',
            'odometer_end'   => 'required|integer|min:0|gte:odometer_start',
            'log_date'       => 'required|date',
            'notes'          => 'nullable|string|max:65535',
        ]);

        $data['vehicle_id'] = $vehicle->id;
        $data['logged_by']  = auth()->id();
        $data['km_driven']  = max(0, (int) $data['odometer_end'] - (int) $data['odometer_start']);

        FSMVehicleMileageLog::create($data);

        // Update vehicle's current mileage if the new reading is higher
        if ((int) $data['odometer_end'] > $vehicle->current_mileage) {
            $vehicle->current_mileage = (int) $data['odometer_end'];
            $vehicle->save();
        }

        return redirect()->route('fsmvehicle.vehicles.show', $vehicle->id)
            ->with('success', 'Mileage log added successfully.');
    }

    public function destroy(int $vehicleId, int $logId)
    {
        $vehicle = FSMVehicle::findOrFail($vehicleId);
        $log     = FSMVehicleMileageLog::where('vehicle_id', $vehicle->id)->findOrFail($logId);
        $log->delete();

        return redirect()->route('fsmvehicle.vehicles.show', $vehicle->id)
            ->with('success', 'Mileage log deleted.');
    }
}
