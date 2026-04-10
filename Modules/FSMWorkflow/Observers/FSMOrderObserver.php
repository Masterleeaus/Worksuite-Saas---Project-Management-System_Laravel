<?php

namespace Modules\FSMWorkflow\Observers;

use Modules\FSMWorkflow\Services\StageActionService;

/**
 * Observes FSMOrder model updates and fires configured stage actions
 * whenever the order moves to a new stage.
 *
 * Registered from FSMWorkflowServiceProvider only when FSMCore is available.
 */
class FSMOrderObserver
{
    public function updated($order): void
    {
        try {
            if (!$order->wasChanged('stage_id')) {
                return;
            }

            app(StageActionService::class)->fireForOrder($order);
        } catch (\Throwable $e) {
            // Observer must never break order saves.
            \Illuminate\Support\Facades\Log::error(
                'FSMWorkflow observer error for order #' . $order->id . ': ' . $e->getMessage()
            );
        }
    }
}
