<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMSaleRecurringAgreement\Http\Controllers\RecurringAgreementController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm-sale-recurring-agreement')
    ->group(function () {
        Route::get('/',            [RecurringAgreementController::class, 'index'])->name('fsmsalerecurringagreement.index');
        Route::get('/{id}/link',   [RecurringAgreementController::class, 'linkForm'])->name('fsmsalerecurringagreement.link_form');
        Route::post('/{id}/link',  [RecurringAgreementController::class, 'link'])->name('fsmsalerecurringagreement.link');
        Route::post('/{id}/unlink',[RecurringAgreementController::class, 'unlink'])->name('fsmsalerecurringagreement.unlink');
    });
