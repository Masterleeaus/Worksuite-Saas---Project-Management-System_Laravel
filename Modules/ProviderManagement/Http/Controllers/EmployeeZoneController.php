<?php

namespace Modules\ProviderManagement\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ProviderManagement\Models\EmployeeZone;

class EmployeeZoneController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Employee Service Zones';
    }

    /**
     * Show zone assignments for an employee.
     */
    public function show(int $employeeId)
    {
        abort_403(!in_array(user()->permission('view_provider_zones'), ['all', 'added', 'owned', 'both'])
            && user()->id !== $employeeId);

        $this->employee      = User::findOrFail($employeeId);
        $this->employeeZones = EmployeeZone::hasZoneRelation()
            ? EmployeeZone::where('employee_id', $employeeId)->with('zone')->get()
            : EmployeeZone::where('employee_id', $employeeId)->get();

        $this->availableZones = [];
        if (EmployeeZone::hasZoneRelation()) {
            $this->availableZones = \Modules\ZoneManagement\Entities\Zone::where('is_active', 1)
                ->when($this->employee->company_id, fn($q) => $q->where('company_id', $this->employee->company_id))
                ->get();
        }

        return view('providermanagement::employee.zones_tab', $this->data);
    }

    /**
     * Sync zone assignments for an employee (replaces all).
     */
    public function sync(Request $request, int $employeeId)
    {
        abort_403(user()->permission('manage_provider_compliance') !== 'all');

        $validated = $request->validate([
            'zone_ids'   => 'nullable|array',
            'zone_ids.*' => ['string', 'regex:/^([0-9]+|[0-9a-fA-F-]{36})$/'],
        ]);

        $zoneIds = $validated['zone_ids'] ?? [];

        EmployeeZone::where('employee_id', $employeeId)->delete();

        if (!empty($zoneIds)) {
            $companyId = User::where('id', $employeeId)->value('company_id') ?: (user()->company_id ?? null);
            $records = array_map(fn($zoneId) => [
                'company_id' => $companyId,
                'employee_id' => $employeeId,
                'zone_id'     => (string) $zoneId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ], $zoneIds);

            DB::table('employee_zones')->insert($records);
        }

        return response()->json(['status' => 'success', 'message' => 'Zone assignments updated.']);
    }
}
