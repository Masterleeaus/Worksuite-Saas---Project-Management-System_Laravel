<?php

namespace Modules\PromotionManagement\Events;

use Illuminate\Queue\SerializesModels;

class MarketingPromotionLifecycleSignal
{
    use SerializesModels;

    public function __construct(
        public string $eventType,
        public string $entityType,
        public string|int $entityId,
        public ?int $tenantId = null,
        public array $facts = [],
    ) {
    }
}
