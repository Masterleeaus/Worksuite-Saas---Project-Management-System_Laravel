<?php

namespace Tests\Unit;

use App\Models\Lead;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMLocation;
use Modules\FSMCore\Models\FSMTemplate;
use Tests\TestCase;

class FsmNativeLeadIntegrationTest extends TestCase
{
    public function test_fsm_order_lead_relation_points_to_native_lead_model(): void
    {
        $relation = (new FSMOrder())->lead();

        $this->assertSame(Lead::class, $relation->getRelated()::class);
        $this->assertSame('lead_id', $relation->getForeignKeyName());
    }

    public function test_native_lead_exposes_fsm_relationships(): void
    {
        $lead = new Lead();

        $ordersRelation = $lead->fsmOrders();
        $locationRelation = $lead->fsmLocation();
        $serviceTypeRelation = $lead->fsmServiceType();

        $this->assertSame(FSMOrder::class, $ordersRelation->getRelated()::class);
        $this->assertSame('lead_id', $ordersRelation->getForeignKeyName());

        $this->assertSame(FSMLocation::class, $locationRelation->getRelated()::class);
        $this->assertSame('fsm_location_id', $locationRelation->getForeignKeyName());

        $this->assertSame(FSMTemplate::class, $serviceTypeRelation->getRelated()::class);
        $this->assertSame('fsm_service_type_id', $serviceTypeRelation->getForeignKeyName());
    }
}
