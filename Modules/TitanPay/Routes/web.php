<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Middleware\VerifyCsrfToken;
use Modules\TitanPay\Http\Controllers\CryptoPaymentController;
use Modules\TitanPay\Http\Controllers\PaymentLinkController;
use Modules\TitanPay\Http\Controllers\PaymentQrController;
use Modules\TitanPay\Http\Controllers\TitanPaySettingsController;

/*
|--------------------------------------------------------------------------
| Public Payment Link Routes  (no auth — clients land here from QR / email)
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['web']], function () {
    Route::get('/pay/{token}', [PaymentLinkController::class, 'show'])->name('titanpay.payment.show');
    Route::post('/pay/{token}/process', [PaymentLinkController::class, 'process'])->name('titanpay.payment.process');

    // Cryptomus return URL after checkout
    Route::get('/titanpay/crypto/return/{invoiceId}', [CryptoPaymentController::class, 'returnUrl'])
        ->name('titanpay.crypto.return');

    // Cryptomus webhook — no CSRF
    Route::post('/titanpay/crypto/webhook', [CryptoPaymentController::class, 'webhook'])
        ->name('titanpay.crypto.webhook')
        ->withoutMiddleware([VerifyCsrfToken::class]);
});

/*
|--------------------------------------------------------------------------
| Admin / Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'account/titanpay', 'as' => 'titanpay.'], function () {

    // Settings
    Route::get('settings', [TitanPaySettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [TitanPaySettingsController::class, 'update'])->name('settings.update');

    // Crypto payment initiation (admin-triggered)
    Route::get('crypto/{invoiceId}/pay', [CryptoPaymentController::class, 'pay'])->name('crypto.pay');

    // Payment Links (admin management)
    Route::get('links/{invoiceId}', [PaymentLinkController::class, 'index'])->name('links.index');
    Route::post('links/{invoiceId}/generate', [PaymentLinkController::class, 'generate'])->name('links.generate');

    // Payment QR codes
    Route::get('qr/{invoiceId}', [PaymentQrController::class, 'index'])->name('qr.index');
    Route::post('qr/{invoiceId}/generate', [PaymentQrController::class, 'generate'])->name('qr.generate');
    Route::get('qr/show/{id}', [PaymentQrController::class, 'show'])->name('qr.show');
});
