<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMSaleAgreement\Http\Controllers\SaleAgreementController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm-sale-agreement')
    ->group(function () {
        Route::get('/', [SaleAgreementController::class, 'index'])->name('fsmsaleagreement.index');
        Route::post('/{id}/propagate', [SaleAgreementController::class, 'propagate'])->name('fsmsaleagreement.propagate');
    });
