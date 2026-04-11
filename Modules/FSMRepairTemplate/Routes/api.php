<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1/fsm/repair-templates')->name('api.fsmrepairtemplate.')->group(function () {
    // API routes
});
