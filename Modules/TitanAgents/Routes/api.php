<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanAgents\Http\Controllers\TitanAgentsController;

/*
|--------------------------------------------------------------------------
| TitanAgents API Routes
|--------------------------------------------------------------------------
| Thin wrapper only. Execution is delegated to TitanZero gateway service.
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::prefix('titan-agents')->group(function () {
        Route::post('/run', [TitanAgentsController::class, 'run'])->name('titanagents.run');
    });
});
