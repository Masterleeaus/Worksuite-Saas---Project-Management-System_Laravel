<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanGo\Http\Controllers\Admin\IssueAdminController;
use Modules\TitanGo\Http\Controllers\Admin\WorkerStatusAdminController;
use Modules\TitanGo\Http\Controllers\Admin\LocationTrackAdminController;

/*
|--------------------------------------------------------------------------
| Titan Go — Admin Web Routes
|--------------------------------------------------------------------------
|
| These routes surface Titan Go field data (issues, statuses, GPS pings)
| in the Worksuite admin panel. They extend the FSMCore layout and require
| the same authenticated web session.
|
| Prefix: /account/titan-go
*/

Route::middleware(['web', 'auth'])
    ->prefix('account/titan-go')
    ->group(function () {

        // Issue reports
        Route::get('issues',         [IssueAdminController::class, 'index'])
            ->name('titango.admin.issues.index');
        Route::post('issues/{id}',   [IssueAdminController::class, 'update'])
            ->name('titango.admin.issues.update');

        // Worker status signals
        Route::get('statuses',       [WorkerStatusAdminController::class, 'index'])
            ->name('titango.admin.statuses.index');

        // GPS tracking
        Route::get('tracking',       [LocationTrackAdminController::class, 'index'])
            ->name('titango.admin.tracking.index');
    });
