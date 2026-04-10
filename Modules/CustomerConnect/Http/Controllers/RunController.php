<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Entities\Campaign;
use Modules\CustomerConnect\Entities\CampaignRun;
use Modules\CustomerConnect\Jobs\BuildCampaignRun;
use Modules\CustomerConnect\Jobs\ExecuteCampaignRun;

class RunController extends AccountBaseController
{
    public function index()
    {
        $this->pageTitle = 'Customer Connect - Runs';

        $runs = CampaignRun::query()
            ->where('company_id', company()->id)
            ->with('campaign')
            ->latest()
            ->paginate(20);

        return view('customerconnect::customerconnect.runs.index', compact('runs'));
    }

    public function show(CampaignRun $run)
    {
        abort_unless((int)$run->company_id === (int)company()->id, 404);
        $run->load(['campaign', 'deliveries']);
        return view('customerconnect::customerconnect.runs.show', compact('run'));
    }

    public function build(Request $request, Campaign $campaign)
    {
        abort_unless((int)$campaign->company_id === (int)company()->id, 404);

        $data = $request->validate([
            'audience_id'  => 'nullable|integer',
            'scheduled_at' => 'nullable|date',
        ]);

        $run = CampaignRun::create([
            'campaign_id'  => $campaign->id,
            'audience_id'  => $data['audience_id'] ?? null,
            'company_id'   => company()->id,
            'status'       => 'queued',
            'scheduled_at' => $data['scheduled_at'] ?? null,
        ]);

        BuildCampaignRun::dispatch($run->id);

        return redirect()->route('customerconnect.runs.show', $run)
            ->with('success', 'Run queued and building deliveries');
    }

    public function execute(CampaignRun $run)
    {
        abort_unless((int)$run->company_id === (int)company()->id, 404);
        ExecuteCampaignRun::dispatch($run->id);
        return back()->with('success', 'Run execution dispatched');
    }
}
