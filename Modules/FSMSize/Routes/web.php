<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMSize\Http\Controllers\FsmSizeController;

Route::middleware(['web', 'auth'])->prefix('fsm/sizes')->name('fsmsize.')->group(function () {
    Route::resource('/', FsmSizeController::class)->parameters(['' => 'fsmsize']);
});
