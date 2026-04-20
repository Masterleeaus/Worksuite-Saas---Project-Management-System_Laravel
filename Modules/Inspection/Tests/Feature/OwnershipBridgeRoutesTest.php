<?php

namespace Modules\Inspection\Tests\Feature;

use Tests\TestCase;

class OwnershipBridgeRoutesTest extends TestCase
{
    public function test_legacy_inspection_routes_remain_registered(): void
    {
        $this->assertTrue(\Route::has('qc-records.index'));
        $this->assertTrue(\Route::has('qc-records.show'));
        $this->assertTrue(\Route::has('qc-records.approve'));
        $this->assertTrue(\Route::has('qc-records.request_reclean'));
    }
}
