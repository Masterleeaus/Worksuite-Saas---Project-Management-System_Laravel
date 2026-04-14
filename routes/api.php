<?php


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

ApiRoute::group(['namespace' => 'App\Http\Controllers'], function () {
    ApiRoute::get('purchased-module', ['as' => 'api.purchasedModule', 'uses' => 'HomeController@installedModule']);
});

ApiRoute::middleware('auth:sanctum')->group(function () {
    ApiRoute::get('leads', [\App\Http\Controllers\Api\LeadController::class, 'index']);
    ApiRoute::get('estimates', [\App\Http\Controllers\Api\EstimateController::class, 'index']);
    ApiRoute::get('tasks', [\App\Http\Controllers\Api\TaskController::class, 'index']);
    ApiRoute::get('invoices', [\App\Http\Controllers\Api\InvoiceController::class, 'index']);
    ApiRoute::get('payments', [\App\Http\Controllers\Api\PaymentController::class, 'index']);
    ApiRoute::get('contracts', [\App\Http\Controllers\Api\ContractController::class, 'index']);
});
