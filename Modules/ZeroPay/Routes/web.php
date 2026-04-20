<?php

use Illuminate\Support\Facades\Route;
use Modules\ZeroPay\Http\Controllers\ZeroPaySessionController;

Route::prefix('zeropay')->name('zeropay.')->group(function () {
    Route::get('session/{token}', [ZeroPaySessionController::class, 'show'])->name('session.show');
    Route::post('session/{token}/select-method', [ZeroPaySessionController::class, 'selectMethod'])->name('session.select-method');
    Route::post('session/{token}/email-invoice', [ZeroPaySessionController::class, 'emailInvoice'])->name('session.email-invoice');

    Route::get('download/invoice/{token}', [ZeroPaySessionController::class, 'downloadInvoice'])->name('download.invoice');
    Route::get('download/receipt/{token}', [ZeroPaySessionController::class, 'downloadReceipt'])->name('download.receipt');
});
