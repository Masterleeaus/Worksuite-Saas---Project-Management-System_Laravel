<?php

namespace Modules\PromotionManagement\Listeners;

use Modules\PromotionManagement\Events\MarketingPromotionLifecycleSignal;
use Modules\PromotionManagement\Services\Ai\ProposalRunner;
use Modules\PromotionManagement\Services\Ai\TitanZeroBridge;

class EmitMarketingPromotionLifecycleSignalToTitanZero
{
    public function handle(MarketingPromotionLifecycleSignal $event): void
    {
        app(TitanZeroBridge::class)->ingestSignal([
            'type' => $event->eventType,
            'payload' => [
                'entity_type' => $event->entityType,
                'entity_id' => $event->entityId,
                'facts' => $event->facts,
            ],
        ], $event->tenantId);

        app(ProposalRunner::class)->proposeAndLog([
            'module' => 'PromotionManagement',
            'event' => $event->eventType,
            'entity_type' => $event->entityType,
            'entity_id' => $event->entityId,
            'facts' => $event->facts,
        ], $event->tenantId);
    }
}
