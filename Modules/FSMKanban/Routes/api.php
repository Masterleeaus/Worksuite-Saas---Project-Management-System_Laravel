<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1/fsm/kanban')->name('api.fsmkanban.')->group(function () {
    // API routes
});
