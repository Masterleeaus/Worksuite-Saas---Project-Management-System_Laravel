<?php

namespace Modules\FSMPortal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMStage;
use Modules\FSMPortal\Models\FSMPortalRecleanRequest;

class PortalJobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List all FSM Orders for the authenticated client.
     * Orders are scoped to locations where partner_id = auth user id.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        $locationIds = FSMLocation::where('partner_id', $userId)->pluck('id');

        $q = FSMOrder::with(['location', 'person', 'stage'])
            ->whereIn('location_id', $locationIds);

        // Filter by status type
        if ($request->filled('status')) {
            $status = $request->get('status');
            $completionStageIds = FSMStage::where('is_completion_stage', true)->pluck('id');

            if ($status === 'completed') {
                $q->whereIn('stage_id', $completionStageIds);
            } elseif ($status === 'in_progress') {
                $q->whereNotNull('date_start')->whereNull('date_end');
            } elseif ($status === 'upcoming') {
                $q->whereNull('date_start')
                  ->whereNotIn('stage_id', $completionStageIds);
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $q->where('scheduled_date_start', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $q->where('scheduled_date_start', '<=', $request->get('date_to') . ' 23:59:59');
        }

        $orders = $q->orderByDesc('scheduled_date_start')->paginate(20)->withQueryString();
        $filter = $request->only(['status', 'date_from', 'date_to']);

        return view('fsmportal::portal.jobs.index', compact('orders', 'filter'));
    }

    /**
     * Show the detail view for a single FSM Order.
     * Access is gated by EnsureClientCanViewOrder middleware.
     */
    public function show(int $id)
    {
        $userId = Auth::id();

        $order = FSMOrder::with(['location', 'person', 'stage', 'template', 'tags'])
            ->findOrFail($id);

        // Ensure client owns this order
        if (!$order->location || (int) $order->location->partner_id !== $userId) {
            abort(403, 'You do not have permission to view this job.');
        }

        // All stages in sequence for the timeline
        $stages = FSMStage::orderBy('sequence')->get();

        // Check-in time (from FSMRoute check-in or direct date_start)
        $checkInTime = $order->date_start;

        // Evidence Vault photos (optional module)
        $evidencePhotos = collect();
        if (class_exists(\Modules\EvidenceVault\Entities\EvidenceSubmission::class)
            && \Illuminate\Support\Facades\Schema::hasTable('evidence_vault_submissions')
        ) {
            $showPhotos = !config('fsmportal.show_photos_on_completion_only', true)
                || ($order->stage && $order->stage->is_completion_stage);

            if ($showPhotos) {
                $evidencePhotos = \Modules\EvidenceVault\Entities\EvidenceSubmission::with('photos')
                    ->where('job_id', $order->id)
                    ->get()
                    ->flatMap(fn($sub) => $sub->photos);
            }
        }

        // Re-clean request (already made for this order by this client?)
        $recleanRequest = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('fsm_portal_reclean_requests')) {
            $recleanRequest = FSMPortalRecleanRequest::where('fsm_order_id', $id)
                ->where('requested_by', $userId)
                ->latest()
                ->first();
        }

        return view('fsmportal::portal.jobs.show', compact(
            'order',
            'stages',
            'checkInTime',
            'evidencePhotos',
            'recleanRequest'
        ));
    }

    /**
     * Handle a client's request for a re-clean.
     * Creates a follow-up FSMActivity (if module available) and records the request.
     */
    public function requestReclean(Request $request, int $id)
    {
        $userId = Auth::id();

        $order = FSMOrder::with('location')->findOrFail($id);

        if (!$order->location || (int) $order->location->partner_id !== $userId) {
            abort(403);
        }

        $data = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate open requests
        $existing = FSMPortalRecleanRequest::where('fsm_order_id', $id)
            ->where('requested_by', $userId)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return redirect()->route('fsmportal.jobs.show', $id)
                ->with('info', 'You already have a pending re-clean request for this job.');
        }

        $reclean = FSMPortalRecleanRequest::create([
            'company_id'   => Auth::user()->company_id ?? null,
            'fsm_order_id' => $id,
            'requested_by' => $userId,
            'reason'       => $data['reason'] ?? null,
            'status'       => 'pending',
        ]);

        // Create a follow-up FSMActivity if the module is available
        $activityId = null;
        if (class_exists(\Modules\FSMActivity\Models\FSMActivity::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_activities')
        ) {
            $activityTypeId = $this->resolveRecleanActivityTypeId($order->company_id);

            $activity = \Modules\FSMActivity\Models\FSMActivity::create([
                'company_id'       => Auth::user()->company_id ?? null,
                'fsm_order_id'     => $id,
                'activity_type_id' => $activityTypeId,
                'summary'          => 'Client requested re-clean for ' . $order->name,
                'note'             => $data['reason'] ?? null,
                'due_date'         => now()->addDay()->toDateString(),
                'state'            => 'open',
            ]);

            $activityId = $activity->id;
        }

        if ($activityId) {
            $reclean->update(['fsm_activity_id' => $activityId]);
        }

        return redirect()->route('fsmportal.jobs.show', $id)
            ->with('success', 'Your re-clean request has been submitted. Our team will be in touch shortly.');
    }

    /**
     * Download a PDF job completion report.
     */
    public function downloadPdf(int $id)
    {
        $userId = Auth::id();

        $order = FSMOrder::with(['location', 'person', 'stage', 'template', 'tags'])
            ->findOrFail($id);

        if (!$order->location || (int) $order->location->partner_id !== $userId) {
            abort(403);
        }

        // Evidence Vault photos (optional)
        $evidencePhotos = collect();
        if (class_exists(\Modules\EvidenceVault\Entities\EvidenceSubmission::class)
            && \Illuminate\Support\Facades\Schema::hasTable('evidence_vault_submissions')
        ) {
            $evidencePhotos = \Modules\EvidenceVault\Entities\EvidenceSubmission::with('photos')
                ->where('job_id', $order->id)
                ->get()
                ->flatMap(fn($sub) => $sub->photos);
        }

        $pdf = app('dompdf.wrapper');
        $pdf->setOption('enable_php', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        $html = view('fsmportal::portal.pdf.job_report', compact('order', 'evidencePhotos'))->render();
        $pdf->loadHTML($html);

        $filename = 'job-report-' . $order->name . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * JSON endpoint for polling the current order status.
     */
    public function statusPoll(int $id)
    {
        $userId = Auth::id();

        $order = FSMOrder::with(['stage', 'location'])->findOrFail($id);

        if (!$order->location || (int) $order->location->partner_id !== $userId) {
            abort(403);
        }

        return response()->json([
            'stage_id'   => $order->stage_id,
            'stage_name' => $order->stage ? $order->stage->name : null,
            'date_start' => $order->date_start?->toISOString(),
            'date_end'   => $order->date_end?->toISOString(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Resolve or create the "Re-clean" FSMActivityType id.
     */
    private function resolveRecleanActivityTypeId(?int $companyId): ?int
    {
        if (!class_exists(\Modules\FSMActivity\Models\FSMActivityType::class)) {
            return null;
        }

        $type = \Modules\FSMActivity\Models\FSMActivityType::where('name', 'Re-clean Request')
            ->first();

        if (!$type) {
            $type = \Modules\FSMActivity\Models\FSMActivityType::create([
                'company_id'  => $companyId,
                'name'        => 'Re-clean Request',
                'icon'        => 'ti-reload',
                'delay_count' => 1,
                'delay_unit'  => 'days',
                'active'      => true,
            ]);
        }

        return $type->id;
    }
}
