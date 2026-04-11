<?php

namespace Modules\ProjectRoadmap\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\ProjectRoadmap\Entities\RoadmapItem;
use Modules\ProjectRoadmap\Entities\FeatureVote;

class RoadmapController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'projectroadmap::app.menu.roadmap';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('projectroadmap', user_modules()) || user()->permission('view_roadmap') == 'none', 403);
            return $next($request);
        });
    }

    /**
     * User-facing roadmap index — Kanban board by status.
     */
    public function index()
    {
        $this->items = RoadmapItem::where('is_public', true)
            ->orderBy('position')
            ->orderByDesc('votes')
            ->get()
            ->groupBy('status');

        $this->statuses      = RoadmapItem::STATUSES;
        $this->statusColors  = RoadmapItem::STATUS_COLORS;
        $this->canManage     = user()->permission('manage_roadmap') !== 'none';
        $this->canVote       = user()->permission('vote_on_feature') !== 'none';

        return view('projectroadmap::roadmap.index', $this->data);
    }
}
