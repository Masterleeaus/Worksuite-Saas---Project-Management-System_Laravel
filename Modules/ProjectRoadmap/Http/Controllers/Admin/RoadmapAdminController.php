<?php

namespace Modules\ProjectRoadmap\Http\Controllers\Admin;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\ProjectRoadmap\Entities\RoadmapItem;
use Modules\ProjectRoadmap\Entities\RoadmapMilestone;

class RoadmapAdminController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'projectroadmap::app.menu.roadmap';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('projectroadmap', user_modules()) || user()->permission('manage_roadmap') == 'none', 403);
            return $next($request);
        });
    }

    // ─── Roadmap Items ────────────────────────────────────────────────

    public function index()
    {
        $this->items = RoadmapItem::orderBy('position')->orderByDesc('votes')->get();
        $this->statuses = RoadmapItem::STATUSES;
        $this->statusColors = RoadmapItem::STATUS_COLORS;

        return view('projectroadmap::admin.index', $this->data);
    }

    public function create()
    {
        $this->statuses = RoadmapItem::STATUSES;
        return view('projectroadmap::admin.create', $this->data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'status'      => 'required|in:' . implode(',', array_keys(RoadmapItem::STATUSES)),
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'is_public'   => 'nullable|boolean',
            'target_release' => 'nullable|string|max:100',
            'release_notes'  => 'nullable|string',
        ]);

        $item = new RoadmapItem();
        $item->company_id      = company()->id;
        $item->name            = $request->name;
        $item->description     = $request->description;
        $item->status          = $request->status;
        $item->category        = $request->category;
        $item->is_public       = $request->boolean('is_public', true);
        $item->target_release  = $request->target_release;
        $item->release_notes   = $request->release_notes;
        $item->added_by        = user()->id;
        $item->save();

        return Reply::success(__('messages.recordSaved'));
    }

    public function edit(int $id)
    {
        $this->item = RoadmapItem::findOrFail($id);
        $this->statuses = RoadmapItem::STATUSES;
        return view('projectroadmap::admin.edit', $this->data);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'status'      => 'required|in:' . implode(',', array_keys(RoadmapItem::STATUSES)),
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'is_public'   => 'nullable|boolean',
            'target_release' => 'nullable|string|max:100',
            'release_notes'  => 'nullable|string',
        ]);

        $item = RoadmapItem::findOrFail($id);
        $item->name            = $request->name;
        $item->description     = $request->description;
        $item->status          = $request->status;
        $item->category        = $request->category;
        $item->is_public       = $request->boolean('is_public', true);
        $item->target_release  = $request->target_release;
        $item->release_notes   = $request->release_notes;
        $item->save();

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    public function destroy(int $id)
    {
        RoadmapItem::findOrFail($id)->delete();
        return Reply::success(__('messages.deleteSuccess'));
    }

    // ─── Milestones ───────────────────────────────────────────────────

    public function milestones()
    {
        $this->milestones = RoadmapMilestone::orderBy('target_date')->get();
        $this->statuses = RoadmapMilestone::STATUSES;
        $this->statusColors = RoadmapMilestone::STATUS_COLORS;
        return view('projectroadmap::admin.milestones', $this->data);
    }

    public function storeMilestone(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'status'      => 'required|in:' . implode(',', array_keys(RoadmapMilestone::STATUSES)),
            'description' => 'nullable|string',
            'target_date' => 'nullable|date',
        ]);

        $ms = new RoadmapMilestone();
        $ms->company_id   = company()->id;
        $ms->title        = $request->title;
        $ms->description  = $request->description;
        $ms->status       = $request->status;
        $ms->target_date  = $request->target_date;
        $ms->added_by     = user()->id;
        $ms->save();

        return Reply::success(__('messages.recordSaved'));
    }

    public function updateMilestone(Request $request, int $id)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'status'         => 'required|in:' . implode(',', array_keys(RoadmapMilestone::STATUSES)),
            'description'    => 'nullable|string',
            'target_date'    => 'nullable|date',
            'completed_date' => 'nullable|date',
        ]);

        $ms = RoadmapMilestone::findOrFail($id);
        $ms->title          = $request->title;
        $ms->description    = $request->description;
        $ms->status         = $request->status;
        $ms->target_date    = $request->target_date;
        $ms->completed_date = $request->completed_date;
        $ms->save();

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    public function destroyMilestone(int $id)
    {
        RoadmapMilestone::findOrFail($id)->delete();
        return Reply::success(__('messages.deleteSuccess'));
    }
}
