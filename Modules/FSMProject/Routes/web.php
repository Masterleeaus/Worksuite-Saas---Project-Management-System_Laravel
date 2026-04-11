<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMProject\Http\Controllers\FsmProjectController;

Route::middleware(['web', 'auth'])->prefix('fsm/project')->name('fsmproject.')->group(function () {
    Route::resource('/', FsmProjectController::class)->parameters(['' => 'fsmproject']);
});
