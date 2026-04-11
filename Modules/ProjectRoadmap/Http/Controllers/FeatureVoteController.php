<?php

namespace Modules\ProjectRoadmap\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\ProjectRoadmap\Entities\FeatureVote;
use Modules\ProjectRoadmap\Entities\RoadmapItem;

class FeatureVoteController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('projectroadmap', user_modules()) || user()->permission('vote_on_feature') == 'none', 403);
            return $next($request);
        });
    }

    /**
     * Toggle a vote for a roadmap item.
     */
    public function vote(Request $request, int $itemId)
    {
        $item = RoadmapItem::findOrFail($itemId);

        $existing = FeatureVote::where('roadmap_item_id', $itemId)
            ->where('user_id', user()->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $item->decrement('votes');
            $voted = false;
        } else {
            FeatureVote::create([
                'roadmap_item_id' => $itemId,
                'user_id'         => user()->id,
                'voter_email'     => user()->email,
            ]);
            $item->increment('votes');
            $voted = true;
        }

        $item->refresh();

        return Reply::dataOnly([
            'voted' => $voted,
            'votes' => $item->votes,
        ]);
    }
}
