<?php

namespace Modules\TitanGo\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMOrder;
use Modules\TitanGo\Models\TitanGoSiteNote;

/**
 * SiteMemoryController
 *
 * Retrieves persistent site context for a visit location:
 * entry instructions, access codes, parking, equipment, preferences, hazards.
 *
 * GET   /api/nexus/v1/jobs/{id}/site-memory
 * POST  /api/nexus/v1/jobs/{id}/site-memory
 */
class SiteMemoryController extends Controller
{
    /**
     * GET /api/nexus/v1/jobs/{id}/site-memory
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        $order = FSMOrder::with('location')
            ->where('company_id', $companyId)
            ->where('person_id', $workerId)
            ->findOrFail($id);

        if (!$order->location_id) {
            return response()->json(['notes' => []]);
        }

        $notes = TitanGoSiteNote::where('company_id', $companyId)
            ->where('location_id', $order->location_id)
            ->get()
            ->groupBy('type');

        return response()->json([
            'location_id' => $order->location_id,
            'notes'       => $notes,
        ]);
    }

    /**
     * POST /api/nexus/v1/jobs/{id}/site-memory
     *
     * Body: { "type": "access_code", "content": "Gate#4521" }
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $companyId = $request->user()->company_id;
        $workerId  = $request->user()->id;

        $order = FSMOrder::where('company_id', $companyId)
            ->where('person_id', $workerId)
            ->findOrFail($id);

        abort_unless($order->location_id, 422, 'Visit has no location assigned.');

        $request->validate([
            'type'    => 'required|in:' . implode(',', TitanGoSiteNote::TYPES),
            'content' => 'required|string|max:2000',
        ]);

        $note = TitanGoSiteNote::create([
            'company_id'  => $companyId,
            'location_id' => $order->location_id,
            'type'        => $request->type,
            'content'     => $request->content,
        ]);

        return response()->json($note, 201);
    }
}
