<?php

use Illuminate\Support\Facades\Route;
use Modules\ZeroPay\Http\Controllers\Api\ZeroPayBankMatchApiController;
use Modules\ZeroPay\Http\Controllers\Api\ZeroPayCallbackController;
use Modules\ZeroPay\Http\Controllers\Api\ZeroPaySessionApiController;

Route::prefix('zeropay')->name('api.zeropay.')->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::post('session', [ZeroPaySessionApiController::class, 'store'])->name('session.store');
        Route::get('session/{id}', [ZeroPaySessionApiController::class, 'show'])->name('session.show');
        Route::post('session/{id}/resend', [ZeroPaySessionApiController::class, 'resend'])->name('session.resend');
        Route::post('session/{id}/mark-cash', [ZeroPaySessionApiController::class, 'markCash'])->name('session.mark-cash');
        Route::post('session/{id}/bank-confirm', [ZeroPaySessionApiController::class, 'bankConfirm'])->name('session.bank-confirm');
        Route::post('session/{id}/voice-followup', [ZeroPaySessionApiController::class, 'voiceFollowup'])->name('session.voice-followup');

        Route::get('matches', [ZeroPayBankMatchApiController::class, 'index'])->name('matches.index');
        Route::post('matches/{id}/accept', [ZeroPayBankMatchApiController::class, 'accept'])->name('matches.accept');
    });

    Route::post('callbacks/stripe', [ZeroPayCallbackController::class, 'stripe'])->name('callbacks.stripe');
    Route::post('callbacks/paypal', [ZeroPayCallbackController::class, 'paypal'])->name('callbacks.paypal');
    Route::post('callbacks/cryptomus', [ZeroPayCallbackController::class, 'cryptomus'])->name('callbacks.cryptomus');
});
