<?php

namespace Modules\FSMStageAction\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\FSMCore\Entities\FsmOrder;
use Modules\FSMStageAction\Entities\FsmStageAction;
use Modules\FSMStageAction\Entities\FsmStageActionLog;

class StageActionDispatcher
{
    /**
     * Fire all active actions registered for the given stage.
     */
    public function dispatch(FsmOrder $order, int $stageId): void
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('fsm_stage_actions')) {
            return;
        }

        $actions = FsmStageAction::where('stage_id', $stageId)
            ->where('active', true)
            ->orderBy('sequence')
            ->get();

        foreach ($actions as $action) {
            $this->run($action, $order);
        }
    }

    private function run(FsmStageAction $action, FsmOrder $order): void
    {
        $status = 'success';
        $message = null;

        try {
            match ($action->action_type) {
                'send_email'    => $this->sendEmail($action, $order),
                'send_sms'      => $this->sendSms($action, $order),
                'set_field'     => $this->setField($action, $order),
                'webhook'       => $this->callWebhook($action, $order),
                'create_invoice' => $this->createInvoice($action, $order),
                default         => null,
            };
        } catch (\Throwable $e) {
            $status = 'failed';
            $message = $e->getMessage();
            Log::warning("FSMStageAction [{$action->id}] failed on order {$order->id}: {$message}");
        }

        FsmStageActionLog::create([
            'stage_action_id' => $action->id,
            'fsm_order_id'    => $order->id,
            'status'          => $status,
            'message'         => $message,
            'ran_at'          => now(),
        ]);
    }

    private function sendEmail(FsmStageAction $action, FsmOrder $order): void
    {
        // Integrate with GlobalSetting email templates
        // Placeholder — wire up to app notification system
        Log::info("FSMStageAction: send_email template={$action->email_template} order={$order->id}");
    }

    private function sendSms(FsmStageAction $action, FsmOrder $order): void
    {
        Log::info("FSMStageAction: send_sms template={$action->sms_template} order={$order->id}");
    }

    private function setField(FsmStageAction $action, FsmOrder $order): void
    {
        if ($action->set_field_name && $order->isFillable($action->set_field_name)) {
            $order->update([$action->set_field_name => $action->set_field_value]);
        }
    }

    private function callWebhook(FsmStageAction $action, FsmOrder $order): void
    {
        Http::withHeaders($action->webhook_headers ?? [])
            ->timeout(10)
            ->post($action->webhook_url, $order->toArray());
    }

    private function createInvoice(FsmStageAction $action, FsmOrder $order): void
    {
        // Mark order as invoiceable; actual invoice created by billing module
        if (\Illuminate\Support\Facades\Schema::hasColumn('fsm_orders', 'invoiced')) {
            $order->update(['invoiced' => true]);
        }
    }
}
