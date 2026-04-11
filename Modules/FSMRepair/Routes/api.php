<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1/fsm/repair')->name('api.fsmrepair.')->group(function () {
    // API routes
});
