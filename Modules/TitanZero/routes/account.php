<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanZero\Http\Controllers\TitanZeroController;

Route::get('/', [TitanZeroController::class, 'index'])->name('index');
Route::get('/help', [TitanZeroController::class, 'help'])->name('help');
Route::get('/chat', [TitanZeroController::class, 'chat'])->name('chat');
