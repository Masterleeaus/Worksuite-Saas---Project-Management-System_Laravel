<?php

namespace Modules\ClientPulse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ClientPulse\Models\ExtrasItem;
use Modules\ClientPulse\Models\ExtrasRequest;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;

class ExtrasRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the extras request form.
     * Provides configurable extras items from the admin, plus a custom note field.
     */
    public function create(Request $request)
    {
        $items = ExtrasItem::active()->get();

        // Find the next upcoming job for this client to attach the extras to
        $userId       = Auth::id();
        $locationIds  = FSMLocation::where('partner_id', $userId)->pluck('id');

        $completionStageIds = FSMStage::where('is_completion_stage', true)->pluck('id');

        $nextJob = FSMOrder::whereIn('location_id', $locationIds)
            ->whereNotIn('stage_id', $completionStageIds)
            ->where('scheduled_date_start', '>=', now())
            ->orderBy('scheduled_date_start')
            ->first();

        return view('clientpulse::portal.extras.form', compact('items', 'nextJob'));
    }

    /**
     * Store an extras request and attach it as a flagged note on the next job.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $data = $request->validate([
            'items'       => 'nullable|array',
            'items.*'     => 'integer|exists:client_pulse_extras_items,id',
            'custom_note' => 'nullable|string|max:2000',
            'job_id'      => 'nullable|integer|exists:fsm_orders,id',
        ]);

        // Ensure client has at least something to request
        if (empty($data['items']) && empty($data['custom_note'])) {
            return back()->withErrors(['items' => 'Please select at least one extra or enter a custom note.']);
        }

        // Resolve the target job (next upcoming job)
        $targetJobId = null;
        if (!empty($data['job_id'])) {
            $order = FSMOrder::with('location')->find($data['job_id']);
            if ($order && $order->location && (int) $order->location->partner_id === $userId) {
                $targetJobId = $order->id;
            }
        }

        if (!$targetJobId) {
            // Find the next upcoming job automatically
            $locationIds = FSMLocation::where('partner_id', $userId)->pluck('id');
            $completionStageIds = FSMStage::where('is_completion_stage', true)->pluck('id');

            $nextJob = FSMOrder::whereIn('location_id', $locationIds)
                ->whereNotIn('stage_id', $completionStageIds)
                ->where('scheduled_date_start', '>=', now())
                ->orderBy('scheduled_date_start')
                ->first();

            $targetJobId = $nextJob?->id;
        }

        $extrasRequest = ExtrasRequest::create([
            'company_id'  => Auth::user()->company_id ?? null,
            'client_id'   => $userId,
            'fsm_order_id' => $targetJobId,
            'items'       => $data['items'] ?? [],
            'custom_note' => $data['custom_note'] ?? null,
            'status'      => ExtrasRequest::STATUS_PENDING,
        ]);

        // Append the extras as a note on the FSM Order (flagged note)
        if ($targetJobId) {
            $this->appendExtrasNoteToOrder($extrasRequest, $targetJobId);
        }

        // Send in-app notification to admin users
        $this->notifyAdmins($extrasRequest);

        return redirect()->route('clientpulse.portal.extras.thanks');
    }

    /**
     * Thank-you page after extras request submission.
     */
    public function thanks()
    {
        return view('clientpulse::portal.extras.thanks');
    }

    // ────────────────────────────────────────────────────────────────────────
    // Helpers
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Append the extras request as a note on the target FSMOrder description.
     * Uses FSMActivity if available, otherwise appends to the order description.
     */
    private function appendExtrasNoteToOrder(ExtrasRequest $extrasRequest, int $orderId): void
    {
        // Build a readable summary of the selected extras
        $itemNames = collect();
        if (!empty($extrasRequest->items)) {
            $itemNames = ExtrasItem::whereIn('id', $extrasRequest->items)->pluck('name');
        }

        $noteLines = [];
        if ($itemNames->isNotEmpty()) {
            $noteLines[] = '⭐ Extras requested: ' . $itemNames->join(', ');
        }
        if ($extrasRequest->custom_note) {
            $noteLines[] = '📝 Custom note: ' . $extrasRequest->custom_note;
        }

        $noteText = '[ClientPulse] ' . implode(' | ', $noteLines)
            . ' (requested by client #' . $extrasRequest->client_id . ')';

        // Create an FSMActivity if the module is available
        if (class_exists(\Modules\FSMActivity\Models\FSMActivity::class)
            && \Illuminate\Support\Facades\Schema::hasTable('fsm_activities')
        ) {
            try {
                $typeId = $this->resolveExtrasActivityTypeId(
                    Auth::user()->company_id ?? null
                );

                \Modules\FSMActivity\Models\FSMActivity::create([
                    'company_id'       => Auth::user()->company_id ?? null,
                    'fsm_order_id'     => $orderId,
                    'activity_type_id' => $typeId,
                    'summary'          => 'Client requested extras for job',
                    'note'             => $noteText,
                    'due_date'         => now()->toDateString(),
                    'state'            => 'open',
                ]);

                $extrasRequest->update(['status' => ExtrasRequest::STATUS_ACKNOWLEDGED]);
                return;
            } catch (\Throwable $e) {
                // Fall through to description append
            }
        }

        // Fallback: append note to order description
        try {
            $order = FSMOrder::find($orderId);
            if ($order) {
                $order->description = trim(($order->description ?? '') . "\n\n" . $noteText);
                $order->save();
            }
        } catch (\Throwable $e) {
            // Non-fatal
        }
    }

    /**
     * Fire an in-app database notification to all admin-role users.
     */
    private function notifyAdmins(ExtrasRequest $extrasRequest): void
    {
        try {
            $client = Auth::user();
            $message = 'Client ' . ($client->name ?? '#' . $client->id)
                . ' has submitted a new extras request'
                . ($extrasRequest->fsm_order_id ? ' for job #' . $extrasRequest->fsm_order_id : '') . '.';

            // Use Laravel's built-in database notifications if the notifications
            // table exists; otherwise, silently skip.
            if (!\Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                return;
            }

            $admins = \App\Models\User::where('is_superadmin', 1)
                ->orWhere('user_type', 'admin')
                ->limit(20)
                ->get();

            foreach ($admins as $admin) {
                \Illuminate\Support\Facades\DB::table('notifications')->insert([
                    'id'              => \Illuminate\Support\Str::uuid()->toString(),
                    'type'            => 'Modules\ClientPulse\Notifications\NewExtrasRequestNotification',
                    'notifiable_type' => \App\Models\User::class,
                    'notifiable_id'   => $admin->id,
                    'data'            => json_encode([
                        'message'          => $message,
                        'extras_request_id' => $extrasRequest->id,
                        'url'              => route('clientpulse.admin.extras.requests'),
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Notifications must never break the main flow
        }
    }

    /**
     * Resolve or create the "Extras Request" FSMActivityType id.
     */
    private function resolveExtrasActivityTypeId(?int $companyId): ?int
    {
        if (!class_exists(\Modules\FSMActivity\Models\FSMActivityType::class)) {
            return null;
        }

        $type = \Modules\FSMActivity\Models\FSMActivityType::where('name', 'Extras Request')->first();

        if (!$type) {
            $type = \Modules\FSMActivity\Models\FSMActivityType::create([
                'company_id'  => $companyId,
                'name'        => 'Extras Request',
                'icon'        => 'ti-plus',
                'delay_count' => 0,
                'delay_unit'  => 'days',
                'active'      => true,
            ]);
        }

        return $type->id;
    }
}
