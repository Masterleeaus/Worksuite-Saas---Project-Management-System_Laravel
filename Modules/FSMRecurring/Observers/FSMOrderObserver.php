<?php

namespace Modules\FSMRecurring\Observers;

use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMStage;
use Modules\FSMRecurring\Models\FSMRecurring;

/**
 * FSMOrderObserver – auto-generates the next recurring job card when a job
 * linked to a recurring schedule is moved to a completion stage.
 *
 * Registered via FSMRecurring EventServiceProvider.
 */
class FSMOrderObserver
{
    /**
     * Called after an FSMOrder is updated.
     *
     * If the order belongs to a recurring schedule and its stage_id was changed
     * to a completion stage, trigger order generation for the recurring schedule
     * so the next job card is created automatically.
     */
    public function updated(FSMOrder $order): void
    {
        if (!$order->wasChanged('stage_id')) {
            return;
        }

        if (!$order->fsm_recurring_id || !$order->stage_id) {
            return;
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('fsm_stages')) {
            return;
        }

        // Query the boolean field directly to avoid fetching the full stage record
        $isCompletion = FSMStage::where('id', $order->stage_id)
            ->value('is_completion_stage');

        if (!$isCompletion) {
            return;
        }

        // Use the existing relationship (may be cached from eager-load)
        $recurring = $order->recurringSchedule;

        if ($recurring instanceof FSMRecurring && $recurring->state === FSMRecurring::STATE_PROGRESS) {
            $recurring->generateOrders();
        }
    }
}
