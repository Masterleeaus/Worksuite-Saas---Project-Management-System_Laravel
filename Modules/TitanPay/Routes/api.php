<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanPay\Http\Controllers\CryptoPaymentController;
use Modules\TitanPay\Http\Controllers\PaymentLinkController;
use Modules\TitanPay\Http\Controllers\PaymentQrController;

/*
|--------------------------------------------------------------------------
| TitanPay API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'titanpay', 'as' => 'api.titanpay.', 'middleware' => ['auth:api']], function () {

    // Generate a payment link for an invoice
    Route::post('links/{invoiceId}/generate', [PaymentLinkController::class, 'generate'])
        ->name('links.generate');

    // Generate a QR code for an invoice
    Route::post('qr/{invoiceId}/generate', [PaymentQrController::class, 'generate'])
        ->name('qr.generate');
});
