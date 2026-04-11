<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMKanban\Http\Controllers\FsmKanbanController;

Route::middleware(['web', 'auth'])->prefix('fsm/kanban')->name('fsmkanban.')->group(function () {
    Route::resource('/', FsmKanbanController::class)->parameters(['' => 'fsmkanban']);
});
