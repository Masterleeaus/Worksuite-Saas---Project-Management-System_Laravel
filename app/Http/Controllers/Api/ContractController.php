<?php

namespace App\Http\Controllers\Api;

use App\Models\Contract;

class ContractController extends BaseTenantIndexController
{
    protected function modelClass(): string
    {
        return Contract::class;
    }
}
