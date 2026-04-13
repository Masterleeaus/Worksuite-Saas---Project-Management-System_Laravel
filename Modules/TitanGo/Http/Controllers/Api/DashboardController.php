<?php

namespace Modules\TitanGo\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;

/**
 * DashboardController
 *
 * Powers the Titan Go "Today" screen — the worker-first landing surface.
 *
 * GET /api/nexus/v1/dashboard
 *
 * Returns:
 *   - today's visits (scheduled for today, assigned to the authenticated worker)
 *   - next upcoming visit preview
 *   - active checklist status per visit
 *   - exception / open issue counts
 */
class DashboardController extends Controller
{
    public function today(Request $request): JsonResponse
    {
        $workerId  = $request->user()->id;
        $companyId = $request->user()->company_id;
        $today     = now()->toDateString();

        $visits = FSMOrder::with(['location', 'stage', 'template'])
            ->where('company_id', $companyId)
            ->where('person_id', $workerId)
            ->whereDate('scheduled_date_start', $today)
            ->orderBy('scheduled_date_start')
            ->get()
            ->map(fn($order) => $this->formatVisit($order));

        $nextVisit = FSMOrder::with(['location', 'stage'])
            ->where('company_id', $companyId)
            ->where('person_id', $workerId)
            ->where('scheduled_date_start', '>', now())
            ->orderBy('scheduled_date_start')
            ->first();

        return response()->json([
            'today'      => $visits,
            'next_visit' => $nextVisit ? $this->formatVisit($nextVisit) : null,
            'summary'    => [
                'total'      => $visits->count(),
                'completed'  => $visits->where('is_complete', true)->count(),
                'remaining'  => $visits->where('is_complete', false)->count(),
            ],
        ]);
    }

    private function formatVisit(FSMOrder $order): array
    {
        $isComplete = $order->stage && $order->stage->is_completion_stage;

        return [
            'id'                    => $order->id,
            'name'                  => $order->name,
            'scheduled_start'       => $order->scheduled_date_start,
            'scheduled_end'         => $order->scheduled_date_end,
            'date_start'            => $order->date_start,
            'date_end'              => $order->date_end,
            'is_complete'           => $isComplete,
            'stage'                 => $order->stage ? $order->stage->name : null,
            'location'              => $order->location ? [
                'id'      => $order->location->id,
                'name'    => $order->location->name,
                'street'  => $order->location->street,
                'city'    => $order->location->city,
                'state'   => $order->location->state,
                'lat'     => $order->location->latitude,
                'lng'     => $order->location->longitude,
                'notes'   => $order->location->notes,
            ] : null,
        ];
    }
}
