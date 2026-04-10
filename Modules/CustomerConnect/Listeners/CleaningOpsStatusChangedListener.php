<?php

namespace Modules\CustomerConnect\Listeners;

use Modules\CustomerConnect\Services\Cleaning\CleaningOpsService;

class CleaningOpsStatusChangedListener
{
    public function __construct(private CleaningOpsService $service) {}

    /**
     * Handle either an event object with a "payload" property, or an array payload.
     */
    public function handle($event): void
    {
        $payload = [];

        if (is_array($event)) {
            $payload = $event;
        } elseif (is_object($event) && isset($event->payload) && is_array($event->payload)) {
            $payload = $event->payload;
        } elseif (is_object($event)) {
            // Best effort: convert public properties to array
            $payload = get_object_vars($event);
        }

        if (!$payload) return;

        $this->service->handleStatusChanged($payload);
    }
}
