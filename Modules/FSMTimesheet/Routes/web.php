<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMTimesheet\Http\Controllers\TimesheetController;
use Modules\FSMTimesheet\Http\Controllers\TimesheetReportController;

// ── Timesheet lines per FSM Order ────────────────────────────────────────────
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/orders')
    ->group(function () {

        Route::get('/{orderId}/timesheets',               [TimesheetController::class, 'index'])->name('fsmtimesheet.timesheets.index');
        Route::get('/{orderId}/timesheets/create',        [TimesheetController::class, 'create'])->name('fsmtimesheet.timesheets.create');
        Route::post('/{orderId}/timesheets',              [TimesheetController::class, 'store'])->name('fsmtimesheet.timesheets.store');
        Route::get('/{orderId}/timesheets/{id}/edit',     [TimesheetController::class, 'edit'])->name('fsmtimesheet.timesheets.edit');
        Route::post('/{orderId}/timesheets/{id}',         [TimesheetController::class, 'update'])->name('fsmtimesheet.timesheets.update');
        Route::post('/{orderId}/timesheets/{id}/delete',  [TimesheetController::class, 'destroy'])->name('fsmtimesheet.timesheets.destroy');

    });

// ── Timesheet Report ─────────────────────────────────────────────────────────
Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/timesheets')
    ->group(function () {

        Route::get('/report',            [TimesheetReportController::class, 'index'])->name('fsmtimesheet.report.index');
        Route::get('/report/export-csv', [TimesheetReportController::class, 'exportCsv'])->name('fsmtimesheet.report.export-csv');

    });
