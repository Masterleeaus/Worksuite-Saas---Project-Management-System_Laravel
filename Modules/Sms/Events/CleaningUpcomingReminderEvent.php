<?php

namespace Modules\Sms\Events;

/**
 * Fired 24 hours before a cleaning job is scheduled.
 */
class CleaningUpcomingReminderEvent
{
    public function __construct(
        public readonly object $order,
    ) {}
}
