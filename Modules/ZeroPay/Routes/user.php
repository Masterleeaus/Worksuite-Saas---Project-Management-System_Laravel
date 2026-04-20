<?php

use Illuminate\Support\Facades\Route;
use Modules\ZeroPay\Http\Controllers\User\ZeroPayUserController;

Route::prefix('account/zeropay/user')->name('zeropay.user.')->group(function () {
    Route::get('sessions', [ZeroPayUserController::class, 'index'])->name('sessions.index');
});
