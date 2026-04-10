<?php

namespace Modules\Sms\Observers;

use Illuminate\Support\Facades\Event;
use Modules\Sms\Events\CleanerCheckedInEvent;
use Modules\Sms\Events\CleanerDispatchedEvent;
use Modules\Sms\Events\CleaningJobCompleteEvent;

/**
 * Observes FSMOrder model to fire cleaning notification events on stage changes.
 *
 * Stage semantics are determined by FSMStage configuration:
 *   - "dispatched" stage: stage where person_id is assigned (non-null) and job is in progress
 *   - "checked-in" stage: identified by stage name containing "check" (configurable)
 *   - "complete" stage: FSMStage::is_completion_stage === true
 *
 * This observer is registered from SmsServiceProvider only when FSMCore is installed.
 */
class FSMOrderObserver
{
    public function updated($order): void
    {
        try {
            if (!$order->wasChanged('stage_id')) {
                return;
            }

            $newStage = $order->stage;

            if (!$newStage) {
                return;
            }

            // Job complete: stage flagged as completion stage
            if ($newStage->is_completion_stage) {
                Event::dispatch(new CleaningJobCompleteEvent($order));
                return;
            }

            $stageName = strtolower($newStage->name ?? '');

            // Cleaner checked in: stage name contains "check" or "arrived"
            if (str_contains($stageName, 'check') || str_contains($stageName, 'arrived')) {
                Event::dispatch(new CleanerCheckedInEvent($order, now()->format('g:i A')));
                return;
            }

            // Cleaner dispatched: stage name contains "dispatch", "on the way", "in progress"
            if (str_contains($stageName, 'dispatch')
                || str_contains($stageName, 'on the way')
                || str_contains($stageName, 'in progress')
                || str_contains($stageName, 'assigned')
            ) {
                $cleaner = $order->person ?? null;
                Event::dispatch(new CleanerDispatchedEvent($order, $cleaner));
            }
        } catch (\Throwable $e) {
            // Observer must never break order updates
        }
    }
}
