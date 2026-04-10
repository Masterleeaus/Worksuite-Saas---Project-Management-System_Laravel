<?php

namespace Modules\Sms\Events;

/**
 * Fired when a cleaner checks in at a job location.
 */
class CleanerCheckedInEvent
{
    public function __construct(
        public readonly object $order,
        public readonly ?string $checkedInAt = null,
    ) {}
}
