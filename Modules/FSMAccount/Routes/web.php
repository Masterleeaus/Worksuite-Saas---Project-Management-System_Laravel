<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMAccount\Http\Controllers\FsmAccountController;

Route::middleware(['web', 'auth'])->prefix('fsm/account')->name('fsmaccount.')->group(function () {
    Route::resource('/', FsmAccountController::class)->parameters(['' => 'fsmaccount']);
});
