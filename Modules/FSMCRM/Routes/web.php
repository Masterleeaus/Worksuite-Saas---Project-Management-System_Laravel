<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMCRM\Http\Controllers\LeadController;
use Modules\FSMCRM\Http\Controllers\ConvertToOrderController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/crm')
    ->group(function () {

        // ── Leads CRUD ───────────────────────────────────────────────────────
        Route::get('/',                    [LeadController::class, 'index'])->name('fsmcrm.leads.index');
        Route::get('/create',             [LeadController::class, 'create'])->name('fsmcrm.leads.create');
        Route::post('/',                   [LeadController::class, 'store'])->name('fsmcrm.leads.store');
        Route::get('/{id}',               [LeadController::class, 'show'])->name('fsmcrm.leads.show');
        Route::get('/{id}/edit',          [LeadController::class, 'edit'])->name('fsmcrm.leads.edit');
        Route::post('/{id}',              [LeadController::class, 'update'])->name('fsmcrm.leads.update');
        Route::post('/{id}/delete',       [LeadController::class, 'destroy'])->name('fsmcrm.leads.destroy');

        // ── Convert Lead → FSM Order ──────────────────────────────────────────
        Route::get('/{leadId}/convert',   [ConvertToOrderController::class, 'create'])->name('fsmcrm.leads.convert');
        Route::post('/{leadId}/convert',  [ConvertToOrderController::class, 'store'])->name('fsmcrm.leads.convert.store');

    });
