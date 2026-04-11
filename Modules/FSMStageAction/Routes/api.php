<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1/fsm/stage-actions')->name('api.fsmstageaction.')->group(function () {
    // API routes
});
