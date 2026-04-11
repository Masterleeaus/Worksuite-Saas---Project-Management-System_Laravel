<?php

namespace Modules\ProjectRoadmap\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ProjectRoadmap\Entities\RoadmapItem;

class RoadmapPublicController extends Controller
{
    /**
     * Public roadmap — no authentication required.
     * Shows only public items.
     */
    public function index(Request $request)
    {
        $items = RoadmapItem::where('is_public', true)
            ->orderBy('position')
            ->orderByDesc('votes')
            ->get()
            ->groupBy('status');

        $statuses     = RoadmapItem::STATUSES;
        $statusColors = RoadmapItem::STATUS_COLORS;

        return view('projectroadmap::public.roadmap', compact('items', 'statuses', 'statusColors'));
    }
}
