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
    ApiRoute::get('leads', ['as' => 'api.leads.index', 'uses' => [\App\Http\Controllers\Api\LeadController::class, 'index']]);
    ApiRoute::get('estimates', ['as' => 'api.estimates.index', 'uses' => [\App\Http\Controllers\Api\EstimateController::class, 'index']]);
    ApiRoute::get('tasks', ['as' => 'api.tasks.index', 'uses' => [\App\Http\Controllers\Api\TaskController::class, 'index']]);
    ApiRoute::get('invoices', ['as' => 'api.invoices.index', 'uses' => [\App\Http\Controllers\Api\InvoiceController::class, 'index']]);
    ApiRoute::get('payments', ['as' => 'api.payments.index', 'uses' => [\App\Http\Controllers\Api\PaymentController::class, 'index']]);
    ApiRoute::get('contracts', ['as' => 'api.contracts.index', 'uses' => [\App\Http\Controllers\Api\ContractController::class, 'index']]);
});
