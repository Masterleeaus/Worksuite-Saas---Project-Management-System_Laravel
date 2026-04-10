<?php

namespace Modules\ClientPulse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;

class CleaningHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the reverse-chronological cleaning history for the authenticated client.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get all locations owned by this client
        $locationIds = FSMLocation::where('partner_id', $userId)->pluck('id');

        // Completion stage IDs
        $completionStageIds = FSMStage::where('is_completion_stage', true)->pluck('id');

        // Completed jobs (history) in reverse-chronological order
        $history = FSMOrder::with(['location', 'person', 'stage'])
            ->whereIn('location_id', $locationIds)
            ->whereIn('stage_id', $completionStageIds)
            ->orderByDesc('date_end')
            ->orderByDesc('scheduled_date_start')
            ->paginate(20)
            ->withQueryString();

        // Attach rating & evidence photo link per job
        $jobIds = $history->pluck('id');

        $ratingsMap = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('client_pulse_job_ratings')) {
            $ratingsMap = \Modules\ClientPulse\Models\JobRating::whereIn('fsm_order_id', $jobIds)
                ->where('client_id', $userId)
                ->pluck('stars', 'fsm_order_id');
        }

        $hasEvidencePhotos = collect();
        if (class_exists(\Modules\EvidenceVault\Entities\EvidenceSubmission::class)
            && \Illuminate\Support\Facades\Schema::hasTable('evidence_vault_submissions')
        ) {
            $hasEvidencePhotos = \Modules\EvidenceVault\Entities\EvidenceSubmission::whereIn('job_id', $jobIds)
                ->pluck('job_id')
                ->flip(); // job_id => index (used as set for O(1) lookup)
        }

        // Upcoming scheduled visits (from FSMRecurring if active)
        $upcomingVisits = collect();
        if (class_exists(\Modules\FSMRecurring\Models\FSMRecurring::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_orders')
        ) {
            $upcomingVisits = FSMOrder::with(['location', 'person', 'stage'])
                ->whereIn('location_id', $locationIds)
                ->whereNotNull('fsm_recurring_id')
                ->whereNull('date_end')
                ->where('scheduled_date_start', '>=', now())
                ->orderBy('scheduled_date_start')
                ->limit(5)
                ->get();
        }

        return view('clientpulse::portal.history.index', compact(
            'history',
            'ratingsMap',
            'hasEvidencePhotos',
            'upcomingVisits',
            'completionStageIds'
        ));
    }
}
