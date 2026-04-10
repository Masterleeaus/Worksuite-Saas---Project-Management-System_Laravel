<?php

namespace Modules\Sms\Events;

/**
 * Fired when a cleaner is dispatched (assigned) to an FSM order.
 */
class CleanerDispatchedEvent
{
    public function __construct(
        public readonly object $order,
        public readonly ?object $cleaner = null,
        public readonly ?int $etaMinutes = null,
    ) {}
}
