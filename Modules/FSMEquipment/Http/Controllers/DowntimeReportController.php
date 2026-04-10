<?php

namespace Modules\FSMEquipment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMEquipment\Models\RepairOrder;
use Modules\FSMCore\Models\FSMEquipment;

class DowntimeReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->filled('from') ? $request->get('from') : now()->subMonths(3)->toDateString();
        $to   = $request->filled('to')   ? $request->get('to')   : now()->toDateString();

        // Load all repair orders in period
        $repairs = RepairOrder::query()
            ->with('equipment')
            ->where('stage', '!=', 'cancelled')
            ->whereNotNull('date_reported')
            ->where('date_reported', '>=', $from)
            ->where('date_reported', '<=', $to . ' 23:59:59')
            ->orderByDesc('date_reported')
            ->get();

        // Aggregate downtime per equipment
        $downtime = [];
        foreach ($repairs as $repair) {
            $eqId   = $repair->equipment_id;
            $eqName = $repair->equipment?->name ?? 'Unknown';
            if (!isset($downtime[$eqId])) {
                $downtime[$eqId] = [
                    'equipment'     => $eqName,
                    'repair_count'  => 0,
                    'total_days'    => 0,
                    'total_cost'    => 0.0,
                    'open_repairs'  => 0,
                ];
            }
            $downtime[$eqId]['repair_count']++;
            $downtime[$eqId]['total_days']  += $repair->downtimeDays() ?? 0;
            $downtime[$eqId]['total_cost']  += (float) ($repair->cost ?? 0);
            if (!in_array($repair->stage, ['completed', 'cancelled'])) {
                $downtime[$eqId]['open_repairs']++;
            }
        }

        usort($downtime, fn($a, $b) => $b['total_days'] <=> $a['total_days']);

        $filter = compact('from', 'to');

        return view('fsmequipment::downtime.index', compact('downtime', 'filter'));
    }
}
