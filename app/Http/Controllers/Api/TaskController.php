<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;

class TaskController extends BaseTenantIndexController
{
    protected function modelClass(): string
    {
        return Task::class;
    }
}
