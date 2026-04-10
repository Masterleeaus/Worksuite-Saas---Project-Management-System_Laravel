<?php

namespace Modules\Sms\Events;

/**
 * Fired when a cleaning job is marked as complete.
 */
class CleaningJobCompleteEvent
{
    public function __construct(
        public readonly object $order,
        public readonly ?string $feedbackLink = null,
    ) {}
}
