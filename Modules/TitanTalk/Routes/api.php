<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanTalk\Http\Controllers\Api\RoomApiController;
use Modules\TitanTalk\Http\Controllers\Api\MessageApiController;

Route::middleware(['auth:sanctum'])
    ->prefix('titan-talk/v1')
    ->name('api.titan.talk.')
    ->group(function () {
        Route::get('/rooms', [RoomApiController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/{room}/messages', [MessageApiController::class, 'index'])->name('messages.index');
        Route::post('/rooms/{room}/messages', [MessageApiController::class, 'store'])->name('messages.store');
    });
