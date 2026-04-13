<?php

namespace Modules\NexusFieldOps\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;

/**
 * NexusDashboardController
 *
 * GET /api/nexus/v1/dashboard
 *
 * Returns the Titan Go "Today" screen payload:
 *   - today's visits for the authenticated worker
 *   - next upcoming visit preview
 *   - open issue count
 *   - FSM stage summary counts
 */
class NexusDashboardController extends Controller
{
    /**
     * GET /api/nexus/v1/dashboard
     */
    public function index(Request $request): JsonResponse
    {
        $worker    = $request->user();
        $companyId = $worker->company_id;
        $today     = now()->toDateString();

        // Today's visits — orders scheduled for today assigned to this worker
        $todayVisits = FSMOrder::with(['location', 'stage'])
            ->where('company_id', $companyId)
            ->where('person_id', $worker->id)
            ->whereDate('scheduled_date_start', $today)
            ->orderBy('scheduled_date_start')
            ->get()
            ->map(fn(FSMOrder $o) => $this->formatVisitSummary($o));

        // Next upcoming visit (after today or later today)
        $next = FSMOrder::with(['location', 'stage'])
            ->where('company_id', $companyId)
            ->where('person_id', $worker->id)
            ->where('scheduled_date_start', '>', now())
            ->whereHas('stage', fn($q) => $q->where('is_completion_stage', false))
            ->orderBy('scheduled_date_start')
            ->first();

        // Open issues for this worker
        $openIssues = 0;
        if (class_exists(\Modules\NexusFieldOps\Models\NexusJobIssue::class)) {
            $openIssues = \Modules\NexusFieldOps\Models\NexusJobIssue::where('company_id', $companyId)
                ->where('worker_id', $worker->id)
                ->where('status', 'open')
                ->count();
        }

        // Stage summary counts for this worker (all non-completed open orders)
        $stageCounts = FSMOrder::where('company_id', $companyId)
            ->where('person_id', $worker->id)
            ->whereHas('stage', fn($q) => $q->where('is_completion_stage', false))
            ->selectRaw('stage_id, count(*) as total')
            ->groupBy('stage_id')
            ->with('stage')
            ->get()
            ->mapWithKeys(fn($row) => [
                optional($row->stage)->name ?? 'Unknown' => (int) $row->total,
            ]);

        return response()->json([
            'worker' => [
                'id'         => $worker->id,
                'name'       => $worker->name,
                'company_id' => $companyId,
            ],
            'today_visits'   => $todayVisits,
            'next_visit'     => $next ? $this->formatVisitSummary($next) : null,
            'open_issues'    => $openIssues,
            'stage_counts'   => $stageCounts,
            'generated_at'   => now()->toIso8601String(),
        ]);
    }

    private function formatVisitSummary(FSMOrder $order): array
    {
        return [
            'id'                   => $order->id,
            'name'                 => $order->name,
            'stage'                => optional($order->stage)->name,
            'is_complete'          => optional($order->stage)->is_completion_stage ?? false,
            'scheduled_start'      => optional($order->scheduled_date_start)->toIso8601String(),
            'scheduled_end'        => optional($order->scheduled_date_end)->toIso8601String(),
            'actual_start'         => optional($order->date_start)->toIso8601String(),
            'actual_end'           => optional($order->date_end)->toIso8601String(),
            'location'             => $order->location ? [
                'id'        => $order->location->id,
                'name'      => $order->location->name,
                'street'    => $order->location->street,
                'city'      => $order->location->city,
                'state'     => $order->location->state,
                'zip'       => $order->location->zip,
                'latitude'  => $order->location->latitude,
                'longitude' => $order->location->longitude,
                'notes'     => $order->location->notes,
            ] : null,
            'priority' => $order->priority,
        ];
    }
}
