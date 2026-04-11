<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMStageAction\Http\Controllers\FsmStageActionController;

Route::middleware(['web', 'auth'])->prefix('fsm/stage-actions')->name('fsmstageaction.')->group(function () {
    Route::resource('/', FsmStageActionController::class)->parameters(['' => 'fsmstageaction']);
});
