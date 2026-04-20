<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMSaleRecurring\Http\Controllers\SaleRecurringController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm-sale-recurring')
    ->group(function () {

        Route::get('/',            [SaleRecurringController::class, 'index'])->name('fsmsalerecurring.index');
        Route::get('/{id}/link',   [SaleRecurringController::class, 'linkForm'])->name('fsmsalerecurring.link_form');
        Route::post('/{id}/link',  [SaleRecurringController::class, 'link'])->name('fsmsalerecurring.link');
        Route::post('/{id}/unlink',[SaleRecurringController::class, 'unlink'])->name('fsmsalerecurring.unlink');

    });
