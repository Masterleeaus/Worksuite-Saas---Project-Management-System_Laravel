<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMRepair\Http\Controllers\FsmRepairController;

Route::middleware(['web', 'auth'])->prefix('fsm/repair')->name('fsmrepair.')->group(function () {
    Route::resource('/', FsmRepairController::class)->parameters(['' => 'fsmrepair']);
});
