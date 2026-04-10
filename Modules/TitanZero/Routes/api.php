<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/aiassistant', function (Request $request) {
    return $request->user();
});

use Modules\TitanZero\Http\Controllers\Api\GatewayController;

Route::prefix('titan-zero')->middleware('auth:api')->group(function () {
    // PASS 1: public internal gateway endpoints (auth/policy hardening happens in later passes)
    Route::post('/docs/propose', [GatewayController::class, 'proposeDocument']);
    Route::post('/agents/run', [GatewayController::class, 'runAgent']);
    Route::post('/signals/ingest', [GatewayController::class, 'ingestSignal']);
});
