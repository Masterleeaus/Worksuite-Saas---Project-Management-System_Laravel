<?php
// UI label: Site Inspections (Pass 2)

namespace Modules\Inspection\Tests\Feature;

use Modules\Inspection\Support\InspectionPermissions;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    /** @test */
    public function permissions_keys_are_defined(): void
    {
        $this->assertSame('inspection.view', InspectionPermissions::VIEW);
        $this->assertSame('add_inspection', InspectionPermissions::ADD_INSPECTION);
        $this->assertSame('request_reclean', InspectionPermissions::REQUEST_RECLEAN);
    }
}
