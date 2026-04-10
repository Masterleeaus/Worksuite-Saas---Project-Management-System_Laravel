
<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanZero\Http\Controllers\Admin\EngineAdminController;

Route::middleware(['web','auth','super-admin'])
    ->prefix('account')
    ->group(function () {

        Route::get('/settings/titan-zero/engines', [EngineAdminController::class, 'index'])
            ->name('titanzero.engines.index');

        Route::get('/settings/titan-zero/engines/{id}/runs', [EngineAdminController::class, 'runs'])
            ->name('titanzero.engines.runs');

        Route::get('/settings/titan-zero/approvals', [EngineAdminController::class, 'approvals'])
            ->name('titanzero.approvals');

        Route::get('/settings/titan-zero/queue', [EngineAdminController::class, 'queue'])
            ->name('titanzero.queue');
    });
