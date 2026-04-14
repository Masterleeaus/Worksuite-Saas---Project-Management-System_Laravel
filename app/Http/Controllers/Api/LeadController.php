<?php

namespace App\Http\Controllers\Api;

use App\Models\Lead;

class LeadController extends BaseTenantIndexController
{
    protected function modelClass(): string
    {
        return Lead::class;
    }
}
