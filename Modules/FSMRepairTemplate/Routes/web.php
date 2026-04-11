<?php

use Illuminate\Support\Facades\Route;
use Modules\FSMRepairTemplate\Http\Controllers\FsmRepairTemplateController;

Route::middleware(['web', 'auth'])->prefix('fsm/repair-templates')->name('fsmrepairtemplate.')->group(function () {
    Route::resource('/', FsmRepairTemplateController::class)->parameters(['' => 'fsmrepairtemplate']);
});
