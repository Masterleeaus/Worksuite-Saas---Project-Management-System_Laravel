<?php

namespace App\Traits;

trait MaintenanceModeTrait
{
    public function checkMaintenanceMode(): bool
    {
        return app()->isDownForMaintenance();
    }
}

