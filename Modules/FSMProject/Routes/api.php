<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1/fsm/project')->name('api.fsmproject.')->group(function () {
    // API routes
});
