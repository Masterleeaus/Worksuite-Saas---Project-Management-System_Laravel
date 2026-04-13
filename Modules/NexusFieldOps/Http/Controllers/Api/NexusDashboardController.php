<?php

namespace Modules\NexusFieldOps\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;

/**
 * NexusDashboardController
 *
 * Provides the worker home screen summary for the Nexus Field Ops Flutter app.
 *
 * Nexus app endpoint: GET /v1/dashboard
 * Backend endpoint  : GET /api/nexus/v1/dashboard
 */
class NexusDashboardController extends Controller
{
    /**
     * GET /api/nexus/v1/dashboard
     *
     * Returns:
     *   - today's assigned jobs (scheduled for today)
     *   - counts: total / open / completed
     *   - next upcoming job
     */
    public function index(Request $request): JsonResponse
    {
        $workerId = $request->user()->id;
        $today    = now()->toDateString();

        $completionStageIds = FSMStage::where('is_completion_stage', true)
            ->pluck('id');

        $allOrders = FSMOrder::with(['location', 'stage'])
            ->where('person_id', $workerId)
            ->get();

        $todayOrders = FSMOrder::with(['location', 'stage'])
            ->where('person_id', $workerId)
            ->whereDate('scheduled_date_start', $today)
            ->orderBy('scheduled_date_start')
            ->get();

        $completedCount = $allOrders->filter(
            fn(FSMOrder $o) => $completionStageIds->contains($o->stage_id)
        )->count();

        $openCount = $allOrders->filter(
            fn(FSMOrder $o) => !$completionStageIds->contains($o->stage_id)
        )->count();

        $nextJob = FSMOrder::with(['location', 'stage'])
            ->where('person_id', $workerId)
            ->whereNotIn('stage_id', $completionStageIds->toArray())
            ->where('scheduled_date_start', '>=', now())
            ->orderBy('scheduled_date_start')
            ->first();

        return response()->json([
            'worker'          => [
                'id'    => $request->user()->id,
                'name'  => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'stats'           => [
                'total_jobs'     => $allOrders->count(),
                'open_jobs'      => $openCount,
                'completed_jobs' => $completedCount,
            ],
            'today_jobs'      => $todayOrders->values(),
            'next_job'        => $nextJob,
        ]);
    }
}
