<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachCampaign;
use Modules\TitanReach\Services\TwilioVoiceService;

class CallCampaignController extends Controller
{
    public function __construct(protected TwilioVoiceService $voice) {}

    public function index()
    {
        $companyId = auth()->user()?->company_id ?? null;
        $campaigns = ReachCampaign::where('channel', 'call')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('titanreach::calls.index', compact('campaigns'));
    }

    public function create()
    {
        return view('titanreach::calls.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'audience_type' => 'required|in:contact_list,segment,manual',
            'audience_id'   => 'nullable|integer',
            'call_script'   => 'nullable|string',
            'scheduled_at'  => 'nullable|date',
        ]);

        $data['channel']    = 'call';
        $data['company_id'] = auth()->user()?->company_id;

        ReachCampaign::create($data);

        return redirect()->route('titanreach.calls.index')->with('success', 'Call campaign created.');
    }

    public function edit(int $id)
    {
        $campaign = ReachCampaign::where('channel', 'call')->findOrFail($id);
        return view('titanreach::calls.create', compact('campaign'));
    }

    public function update(Request $request, int $id)
    {
        $campaign = ReachCampaign::where('channel', 'call')->findOrFail($id);

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'audience_type' => 'required|in:contact_list,segment,manual',
            'audience_id'   => 'nullable|integer',
            'call_script'   => 'nullable|string',
            'scheduled_at'  => 'nullable|date',
        ]);

        $campaign->update($data);

        return redirect()->route('titanreach.calls.index')->with('success', 'Campaign updated.');
    }

    public function destroy(int $id)
    {
        ReachCampaign::where('channel', 'call')->findOrFail($id)->delete();

        return redirect()->route('titanreach.calls.index')->with('success', 'Campaign deleted.');
    }

    public function run(int $id)
    {
        $campaign = ReachCampaign::where('channel', 'call')->findOrFail($id);
        $campaign->update(['status' => 'running']);

        return redirect()->route('titanreach.calls.index')->with('success', 'Call campaign started.');
    }
}
