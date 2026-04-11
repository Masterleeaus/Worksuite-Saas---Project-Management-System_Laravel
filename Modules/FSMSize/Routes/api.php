<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1/fsm/sizes')->name('api.fsmsize.')->group(function () {
    // API routes
});
