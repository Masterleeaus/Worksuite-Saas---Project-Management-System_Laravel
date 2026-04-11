<?php

use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\BookingReportController;
use Modules\Report\Http\Controllers\ChemicalUsageReportController;
use Modules\Report\Http\Controllers\CleanerPerformanceReportController;
use Modules\Report\Http\Controllers\RevenueByZoneReportController;
use Modules\Report\Http\Controllers\RouteEfficiencyReportController;

/*
|--------------------------------------------------------------------------
| FSM Report Module — Web Routes
|--------------------------------------------------------------------------
|
| All routes sit under account/reports/fsm so they never clash with core
| Worksuite report routes (which use account/reports without the /fsm prefix).
|
*/

Route::middleware(['web', 'auth'])
    ->prefix('account/reports/fsm')
    ->as('report.fsm.')
    ->group(function () {

        // ── Booking Performance ────────────────────────────────────────────
        Route::get('/bookings', [BookingReportController::class, 'index'])->name('bookings');
        Route::get('/bookings/chart-data', [BookingReportController::class, 'chartData'])->name('bookings.chart');
        Route::get('/bookings/export', [BookingReportController::class, 'export'])->name('bookings.export');

        // ── Cleaner Scorecard ─────────────────────────────────────────────
        Route::get('/cleaner-scorecard', [CleanerPerformanceReportController::class, 'index'])->name('cleaner-scorecard');
        Route::get('/cleaner-scorecard/{cleanerId}', [CleanerPerformanceReportController::class, 'show'])->name('cleaner-scorecard.show');
        Route::get('/cleaner-scorecard/export', [CleanerPerformanceReportController::class, 'export'])->name('cleaner-scorecard.export');

        // ── Revenue by Zone ───────────────────────────────────────────────
        Route::get('/zone-revenue', [RevenueByZoneReportController::class, 'index'])->name('zone-revenue');
        Route::get('/zone-revenue/chart-data', [RevenueByZoneReportController::class, 'chartData'])->name('zone-revenue.chart');
        Route::get('/zone-revenue/export', [RevenueByZoneReportController::class, 'export'])->name('zone-revenue.export');

        // ── Chemical Usage ────────────────────────────────────────────────
        Route::get('/chemical-usage', [ChemicalUsageReportController::class, 'index'])->name('chemical-usage');
        Route::get('/chemical-usage/export', [ChemicalUsageReportController::class, 'export'])->name('chemical-usage.export');

        // ── Route Efficiency ──────────────────────────────────────────────
        Route::get('/route-efficiency', [RouteEfficiencyReportController::class, 'index'])->name('route-efficiency');
        Route::get('/route-efficiency/export', [RouteEfficiencyReportController::class, 'export'])->name('route-efficiency.export');
    });
