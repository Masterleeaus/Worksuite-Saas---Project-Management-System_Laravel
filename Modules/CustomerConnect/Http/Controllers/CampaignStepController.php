<?php

namespace Modules\CustomerConnect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CustomerConnect\Entities\Campaign;
use Modules\CustomerConnect\Entities\CampaignStep;
use Modules\CustomerConnect\Enums\Channel;
use Modules\CustomerConnect\Enums\StepType;

class CampaignStepController extends Controller
{
    public function index(Campaign $campaign)
    {
        $campaign->load('steps');
        $channels = Channel::all();
        $types = StepType::all();
        return view('customerconnect::customerconnect.campaigns.steps.index', compact('campaign', 'channels', 'types'));
    }

    public function store(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'channel' => 'nullable|string',
            'delay_minutes' => 'nullable|integer|min:0',
            'subject' => 'nullable|string|max:191',
            'body' => 'nullable|string',
        ]);

        $position = (int)($campaign->steps()->max('position') ?? 0) + 1;

        $campaign->steps()->create(array_merge($data, [
            'position' => $position,
        ]));

        return back()->with('success', 'Step added');
    }

    public function edit(Campaign $campaign, CampaignStep $step)
    {
        abort_unless($step->campaign_id === $campaign->id, 404);

        $channels = Channel::all();
        $types = StepType::all();

        return view('customerconnect::customerconnect.campaigns.steps.edit', compact('campaign', 'step', 'channels', 'types'));
    }

    public function update(Request $request, Campaign $campaign, CampaignStep $step)
    {
        abort_unless($step->campaign_id === $campaign->id, 404);

        $data = $request->validate([
            'position' => 'nullable|integer|min:1',
            'type' => 'required|string',
            'channel' => 'nullable|string',
            'delay_minutes' => 'nullable|integer|min:0',
            'subject' => 'nullable|string|max:191',
            'body' => 'nullable|string',
        ]);

        $step->update($data);

        return redirect()->route('customerconnect.campaigns.steps.index', $campaign)->with('success', 'Step updated');
    }

    public function destroy(Campaign $campaign, CampaignStep $step)
    {
        abort_unless($step->campaign_id === $campaign->id, 404);
        $step->delete();
        return back()->with('success', 'Step deleted');
    }

    public function reorder(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        $ids = $data['order'];
        $pos = 1;
        foreach ($ids as $id) {
            CampaignStep::query()
                ->where('campaign_id', $campaign->id)
                ->where('id', $id)
                ->update(['position' => $pos]);
            $pos++;
        }

        return back()->with('success', 'Steps reordered');
    }
}
