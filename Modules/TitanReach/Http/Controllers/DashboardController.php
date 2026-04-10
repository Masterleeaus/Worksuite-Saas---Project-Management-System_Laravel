<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachConversation;
use Modules\TitanReach\Models\ReachCampaign;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()?->company_id ?? null;

        $conversationCounts = ReachConversation::when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->selectRaw('channel, count(*) as total')
            ->groupBy('channel')
            ->pluck('total', 'channel')
            ->toArray();

        $campaignCounts = ReachCampaign::when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $recentConversations = ReachConversation::with('contact')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view('titanreach::dashboard.index', compact(
            'conversationCounts',
            'campaignCounts',
            'recentConversations'
        ));
    }
}
