<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Modules\CustomerConnect\Entities\Campaign;
use Modules\CustomerConnect\Http\Requests\StoreCampaignRequest;
use Modules\CustomerConnect\Http\Requests\UpdateCampaignRequest;

class CampaignController extends AccountBaseController
{
    public function index()
    {
        $this->pageTitle = 'Customer Connect - Campaigns';

        $campaigns = Campaign::query()
            ->where('company_id', company()->id)
            ->latest()
            ->paginate(20);

        return view('customerconnect::customerconnect.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('customerconnect::customerconnect.campaigns.create');
    }

    public function store(StoreCampaignRequest $request)
    {
        $data               = $request->validated();
        $data['company_id'] = company()->id;

        $campaign = Campaign::create($data);

        return redirect()->route('customerconnect.campaigns.edit', $campaign)->with('success', 'Campaign created');
    }

    public function show(Campaign $campaign)
    {
        $this->authorizeCompany($campaign);
        $campaign->load(['steps', 'runs']);
        return view('customerconnect::customerconnect.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        $this->authorizeCompany($campaign);
        $campaign->load('steps');
        return view('customerconnect::customerconnect.campaigns.edit', compact('campaign'));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    {
        $this->authorizeCompany($campaign);
        $campaign->update($request->validated());
        return back()->with('success', 'Campaign updated');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorizeCompany($campaign);
        $campaign->delete();
        return redirect()->route('customerconnect.campaigns.index')->with('success', 'Campaign deleted');
    }

    public function activate(Campaign $campaign)
    {
        $this->authorizeCompany($campaign);
        $campaign->update(['status' => 'active']);
        return back()->with('success', 'Campaign activated');
    }

    public function pause(Campaign $campaign)
    {
        $this->authorizeCompany($campaign);
        $campaign->update(['status' => 'paused']);
        return back()->with('success', 'Campaign paused');
    }

    public function preview(Campaign $campaign)
    {
        $this->authorizeCompany($campaign);
        $campaign->load('steps');
        return view('customerconnect::customerconnect.campaigns.preview', compact('campaign'));
    }

    private function authorizeCompany(Campaign $campaign): void
    {
        abort_unless((int)$campaign->company_id === (int)company()->id, 404);
    }
}
