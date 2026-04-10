<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMPortal\Http\Controllers\PortalJobController;

Route::middleware(['web', 'auth'])
    ->prefix('portal')
    ->name('fsmportal.')
    ->group(function () {

        // FSM Jobs portal list
        Route::get('/jobs', [PortalJobController::class, 'index'])->name('jobs.index');

        // FSM Job detail
        Route::get('/jobs/{id}', [PortalJobController::class, 'show'])->name('jobs.show');

        // Re-clean request
        Route::post('/jobs/{id}/reclean', [PortalJobController::class, 'requestReclean'])->name('jobs.reclean');

        // PDF download
        Route::get('/jobs/{id}/pdf', [PortalJobController::class, 'downloadPdf'])->name('jobs.pdf');

        // Status polling (JSON)
        Route::get('/jobs/{id}/status', [PortalJobController::class, 'statusPoll'])->name('jobs.status');

    });
