<?php

namespace Tests\Unit;

use App\Models\Estimate;
use Modules\FSMCore\Models\FSMOrder;
use Tests\TestCase;

class FsmEstimateIntegrationTest extends TestCase
{
    public function test_fsm_order_estimate_relation_points_to_native_estimate_model(): void
    {
        $relation = (new FSMOrder())->estimate();

        $this->assertSame(Estimate::class, $relation->getRelated()::class);
        $this->assertSame('estimate_id', $relation->getForeignKeyName());
    }

    public function test_native_estimate_exposes_fsm_orders_relation(): void
    {
        $relation = (new Estimate())->fsmOrders();

        $this->assertSame(FSMOrder::class, $relation->getRelated()::class);
        $this->assertSame('estimate_id', $relation->getForeignKeyName());
    }
}
