<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachCampaign;
use Modules\TitanReach\Services\CampaignDispatchService;

class CampaignController extends Controller
{
    public function __construct(protected CampaignDispatchService $dispatcher) {}

    public function index(Request $request)
    {
        $companyId = auth()->user()?->company_id ?? null;
        $query = ReachCampaign::when($companyId, fn ($q) => $q->where('company_id', $companyId));

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('channel')) {
            $query->where('channel', $request->input('channel'));
        }

        $campaigns = $query->orderByDesc('created_at')->paginate(20);

        return view('titanreach::campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('titanreach::campaigns.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'channel'       => 'required|in:whatsapp,sms,telegram,call,multi',
            'audience_type' => 'required|in:contact_list,segment,manual',
            'audience_id'   => 'nullable|integer',
            'content'       => 'nullable|string',
            'call_script'   => 'nullable|string',
            'scheduled_at'  => 'nullable|date',
        ]);

        $data['company_id'] = auth()->user()?->company_id;

        ReachCampaign::create($data);

        return redirect()->route('titanreach.campaigns.index')->with('success', 'Campaign created.');
    }

    public function edit(int $id)
    {
        $campaign = ReachCampaign::findOrFail($id);
        return view('titanreach::campaigns.create', compact('campaign'));
    }

    public function update(Request $request, int $id)
    {
        $campaign = ReachCampaign::findOrFail($id);

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'channel'       => 'required|in:whatsapp,sms,telegram,call,multi',
            'audience_type' => 'required|in:contact_list,segment,manual',
            'audience_id'   => 'nullable|integer',
            'content'       => 'nullable|string',
            'call_script'   => 'nullable|string',
            'scheduled_at'  => 'nullable|date',
        ]);

        $campaign->update($data);

        return redirect()->route('titanreach.campaigns.index')->with('success', 'Campaign updated.');
    }

    public function destroy(int $id)
    {
        ReachCampaign::findOrFail($id)->delete();

        return redirect()->route('titanreach.campaigns.index')->with('success', 'Campaign deleted.');
    }

    public function run(int $id)
    {
        $campaign = ReachCampaign::findOrFail($id);
        $this->dispatcher->dispatch($campaign);

        return redirect()->route('titanreach.campaigns.index')->with('success', 'Campaign started.');
    }
}
