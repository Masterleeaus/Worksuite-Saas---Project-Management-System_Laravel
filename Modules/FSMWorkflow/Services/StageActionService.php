<?php

namespace Modules\FSMWorkflow\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMWorkflow\Models\FSMStageAction;

/**
 * Executes automated stage actions when an FSM Order moves to a new stage.
 *
 * Supported action types:
 *   send_sms        – SMS via the Sms module (if installed)
 *   send_email      – Email via Laravel Mail to the assigned worker or location client
 *   create_activity – Creates an FSMActivity record (if FSMActivity module installed)
 *   create_invoice  – Creates a draft FSMSales invoice (if FSMSales module installed)
 *   webhook         – HTTP POST to configured webhook_url with order JSON
 *   custom          – No-op placeholder; override this class to add custom logic
 */
class StageActionService
{
    /**
     * Fire all active stage actions for the order's new stage.
     */
    public function fireForOrder(FSMOrder $order): void
    {
        if (!$order->stage_id) {
            return;
        }

        $actions = FSMStageAction::active()
            ->forStage($order->stage_id)
            ->orderBy('sequence')
            ->get();

        foreach ($actions as $action) {
            try {
                $this->dispatch($action, $order);
            } catch (\Throwable $e) {
                // Never let one failing action abort subsequent ones or the stage update.
                Log::warning("FSMWorkflow: action #{$action->id} ({$action->action_type}) failed for order #{$order->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Fire a single action against a given order (used for test-fire from the admin UI).
     */
    public function fireAction(FSMStageAction $action, FSMOrder $order): void
    {
        $this->dispatch($action, $order);
    }

    // ── Private dispatch ──────────────────────────────────────────────────────

    private function dispatch(FSMStageAction $action, FSMOrder $order): void
    {
        match ($action->action_type) {
            'send_sms'        => $this->doSendSms($action, $order),
            'send_email'      => $this->doSendEmail($action, $order),
            'create_activity' => $this->doCreateActivity($action, $order),
            'create_invoice'  => $this->doCreateInvoice($action, $order),
            'webhook'         => $this->doWebhook($action, $order),
            'custom'          => $this->doCustom($action, $order),
            default           => Log::debug("FSMWorkflow: unknown action_type '{$action->action_type}' – skipped."),
        };
    }

    // ── send_sms ──────────────────────────────────────────────────────────────

    private function doSendSms(FSMStageAction $action, FSMOrder $order): void
    {
        if (!class_exists(\Modules\Sms\Services\CleaningNotificationService::class)) {
            Log::debug('FSMWorkflow: Sms module not installed – send_sms skipped.');
            return;
        }

        $notifiable = $order->person; // assigned worker

        if (!$notifiable) {
            return;
        }

        $message = $this->buildMessage($action, $order);

        // Use the raw Twilio helper if available, otherwise log.
        if (function_exists('send_twilio_sms')) {
            send_twilio_sms($notifiable, $message);
        } else {
            Log::info("FSMWorkflow [SMS] to worker #{$notifiable->id}: {$message}");
        }
    }

    // ── send_email ────────────────────────────────────────────────────────────

    private function doSendEmail(FSMStageAction $action, FSMOrder $order): void
    {
        $notifiable = $order->person;

        if (!$notifiable || !$notifiable->email) {
            return;
        }

        $message = $this->buildMessage($action, $order);
        $stage   = $order->stage?->name ?? 'Updated';
        $subject = "FSM Order {$order->name} – Stage: {$stage}";

        Mail::raw($message, function ($mail) use ($notifiable, $subject) {
            $mail->to($notifiable->email)->subject($subject);
        });
    }

    // ── create_activity ───────────────────────────────────────────────────────

    private function doCreateActivity(FSMStageAction $action, FSMOrder $order): void
    {
        if (!class_exists(\Modules\FSMActivity\Models\FSMActivity::class)) {
            Log::debug('FSMWorkflow: FSMActivity module not installed – create_activity skipped.');
            return;
        }

        \Modules\FSMActivity\Models\FSMActivity::create([
            'company_id'       => $order->company_id,
            'fsm_order_id'     => $order->id,
            'activity_type_id' => $action->activity_type_id,
            'summary'          => $action->name ?? ('Stage action: ' . ($order->stage?->name ?? 'Unknown')),
            'note'             => $this->buildMessage($action, $order),
            'due_date'         => now()->addDay()->toDateString(),
            'state'            => 'open',
        ]);
    }

    // ── create_invoice ────────────────────────────────────────────────────────

    private function doCreateInvoice(FSMStageAction $action, FSMOrder $order): void
    {
        if (!class_exists(\Modules\FSMSales\Services\InvoiceGenerationService::class)) {
            Log::debug('FSMWorkflow: FSMSales module not installed – create_invoice skipped.');
            return;
        }

        app(\Modules\FSMSales\Services\InvoiceGenerationService::class)
            ->createFromOrderCompletion($order);
    }

    // ── webhook ───────────────────────────────────────────────────────────────

    private function doWebhook(FSMStageAction $action, FSMOrder $order): void
    {
        if (!$action->webhook_url) {
            return;
        }

        $payload = [
            'event'     => 'stage_changed',
            'order_id'  => $order->id,
            'order_ref' => $order->name,
            'stage_id'  => $order->stage_id,
            'stage'     => $order->stage?->name,
            'action_id' => $action->id,
        ];

        if ($action->custom_payload) {
            $extra = json_decode($action->custom_payload, true);
            if (is_array($extra)) {
                $payload = array_merge($payload, $extra);
            }
        }

        Http::timeout(10)->post($action->webhook_url, $payload);
    }

    // ── custom ────────────────────────────────────────────────────────────────

    protected function doCustom(FSMStageAction $action, FSMOrder $order): void
    {
        // Override this method in a subclass to add custom logic.
        Log::debug("FSMWorkflow: custom action #{$action->id} fired for order #{$order->id} – no handler registered.");
    }

    // ── helpers ───────────────────────────────────────────────────────────────

    /**
     * Build a message from the action's custom_payload (used as message template),
     * substituting basic order tokens like {order_ref} and {stage}.
     */
    private function buildMessage(FSMStageAction $action, FSMOrder $order): string
    {
        $template = $action->custom_payload
            ?? "Your FSM Order {$order->name} has been moved to stage: " . ($order->stage?->name ?? 'Unknown') . '.';

        return str_replace(
            ['{order_ref}', '{stage}', '{worker}', '{location}'],
            [
                $order->name,
                $order->stage?->name ?? '',
                $order->person?->name ?? '',
                $order->location?->name ?? '',
            ],
            $template
        );
    }
}
