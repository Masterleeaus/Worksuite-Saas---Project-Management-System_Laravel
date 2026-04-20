<?php

use Illuminate\Support\Facades\Route;
use Modules\ZeroPay\Http\Controllers\Web\SessionController;

Route::prefix('zeropay')->name('zeropay.')->group(function () {
    Route::get('session/{token}', [SessionController::class, 'show'])->name('session.show');
    Route::post('session/{token}/select-method', [SessionController::class, 'selectMethod'])->name('session.select-method');
    Route::post('session/{token}/email-invoice', [SessionController::class, 'emailInvoice'])->name('session.email-invoice');

    Route::get('download/invoice/{token}', [SessionController::class, 'downloadInvoice'])->name('download.invoice');
    Route::get('download/receipt/{token}', [SessionController::class, 'downloadReceipt'])->name('download.receipt');
});
