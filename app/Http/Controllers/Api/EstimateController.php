<?php

namespace App\Http\Controllers\Api;

use App\Models\Estimate;

class EstimateController extends BaseTenantIndexController
{
    protected function modelClass(): string
    {
        return Estimate::class;
    }
}
