<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1/fsm/account')->name('api.fsmaccount.')->group(function () {
    // API routes
});
