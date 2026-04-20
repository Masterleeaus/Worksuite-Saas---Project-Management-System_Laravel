<?php

use Illuminate\Support\Facades\Route;
use Modules\ZeroPay\Http\Controllers\Admin\ZeroPayAdminController;

Route::prefix('account/zeropay')->name('zeropay.admin.')->group(function () {
    Route::get('/', [ZeroPayAdminController::class, 'dashboard'])->name('dashboard');
    Route::match(['get', 'post'], 'settings', [ZeroPayAdminController::class, 'settings'])->name('settings');
});
