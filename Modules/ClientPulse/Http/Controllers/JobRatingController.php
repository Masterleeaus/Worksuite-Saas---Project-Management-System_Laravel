<?php

namespace Modules\ClientPulse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ClientPulse\Models\JobRating;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMStage;

class JobRatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the rating form for a completed job.
     */
    public function show(int $jobId)
    {
        $userId = Auth::id();

        $order = FSMOrder::with(['location', 'person', 'stage'])->findOrFail($jobId);

        $this->authoriseClientOrder($order, $userId);

        // Ensure the job is actually complete
        $isComplete = $order->stage && $order->stage->is_completion_stage;
        if (!$isComplete) {
            return redirect()->route('clientpulse.portal.history.index')
                ->with('info', 'Ratings can only be submitted for completed jobs.');
        }

        // Check for existing rating
        $existing = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('client_pulse_job_ratings')) {
            $existing = JobRating::where('fsm_order_id', $jobId)
                ->where('client_id', $userId)
                ->first();
        }

        return view('clientpulse::portal.rating.form', compact('order', 'existing'));
    }

    /**
     * Store a new rating for a completed job.
     */
    public function store(Request $request, int $jobId)
    {
        $userId = Auth::id();

        $order = FSMOrder::with(['location', 'person', 'stage'])->findOrFail($jobId);

        $this->authoriseClientOrder($order, $userId);

        // Ensure job is complete
        if (!$order->stage || !$order->stage->is_completion_stage) {
            abort(422, 'Ratings can only be submitted for completed jobs.');
        }

        $data = $request->validate([
            'stars'   => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        // Upsert the rating
        JobRating::updateOrCreate(
            [
                'fsm_order_id' => $jobId,
                'client_id'    => $userId,
            ],
            [
                'company_id' => Auth::user()->company_id ?? null,
                'cleaner_id' => $order->person_id,
                'stars'      => $data['stars'],
                'comment'    => $data['comment'] ?? null,
                'rated_at'   => now(),
            ]
        );

        return redirect()->route('clientpulse.portal.rating.thanks', $jobId);
    }

    /**
     * Thank-you page after rating submission.
     */
    public function thanks(int $jobId)
    {
        $userId = Auth::id();

        $order = FSMOrder::with(['location', 'person', 'stage'])->findOrFail($jobId);

        $this->authoriseClientOrder($order, $userId);

        return view('clientpulse::portal.rating.thanks', compact('order'));
    }

    // ────────────────────────────────────────────────────────────────────────
    // Helpers
    // ────────────────────────────────────────────────────────────────────────

    private function authoriseClientOrder(FSMOrder $order, int $userId): void
    {
        if (!$order->location || (int) $order->location->partner_id !== $userId) {
            abort(403, 'You do not have permission to rate this job.');
        }
    }
}
