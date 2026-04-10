<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix("/auth")->group(function () {
    Route::post('login', [App\Http\Controllers\Api\v1\AuthController::class,"login"]);
    Route::post('send-otp', 'App\Http\Controllers\API\v1\AuthController@send_otp');
    Route::post('signup', 'App\Http\Controllers\API\v1\AuthController@signup');
 
});

Route::middleware("auth:api")->prefix("/auth")->group(function () {
    Route::post('update/device-info', [App\Http\Controllers\Api\v1\AuthController::class,'update_device_info']);
    Route::post('update-password', [App\Http\Controllers\Api\v1\AuthController::class,'update_password']);

});
