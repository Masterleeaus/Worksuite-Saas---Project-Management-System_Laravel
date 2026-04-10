<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMServiceAgreement\Http\Controllers\AgreementController;

Route::middleware(['web', 'auth'])
    ->prefix('account/fsm/agreements')
    ->group(function () {

        Route::get('/',                [AgreementController::class, 'index'])->name('fsmserviceagreement.agreements.index');
        Route::get('/create',          [AgreementController::class, 'create'])->name('fsmserviceagreement.agreements.create');
        Route::post('/',               [AgreementController::class, 'store'])->name('fsmserviceagreement.agreements.store');
        Route::get('/{id}',            [AgreementController::class, 'show'])->name('fsmserviceagreement.agreements.show');
        Route::get('/{id}/edit',       [AgreementController::class, 'edit'])->name('fsmserviceagreement.agreements.edit');
        Route::put('/{id}',            [AgreementController::class, 'update'])->name('fsmserviceagreement.agreements.update');
        Route::delete('/{id}',         [AgreementController::class, 'destroy'])->name('fsmserviceagreement.agreements.destroy');
        Route::post('/{id}/activate',  [AgreementController::class, 'activate'])->name('fsmserviceagreement.agreements.activate');
        Route::post('/{id}/cancel',    [AgreementController::class, 'cancel'])->name('fsmserviceagreement.agreements.cancel');
    });
