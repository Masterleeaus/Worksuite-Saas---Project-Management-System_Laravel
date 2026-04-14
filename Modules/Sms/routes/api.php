<?php

use Illuminate\Support\Facades\Route;
use Modules\Sms\Http\Controllers\Gateway\Api\V1\Admin\SmsGatewayConfigApiController;

/*
|--------------------------------------------------------------------------
| API Routes — Sms Module (merged Sms + SMSModule)
|--------------------------------------------------------------------------
|
| Gateway config endpoints are versioned under api/v1 via the
| RouteServiceProvider. This file registers the /admin/sms-config group.
|
*/

// -----------------------------------------------------------------------
// API v1 – Admin gateway configuration (merged from SMSModule)
// -----------------------------------------------------------------------
Route::group([
    'prefix'     => 'admin',
    'as'         => 'admin.',
    'middleware' => ['auth:api'],
], function () {
    Route::group(['prefix' => 'sms-config'], function () {
        Route::get('get', [SmsGatewayConfigApiController::class, 'smsConfigGet']);
        Route::put('set', [SmsGatewayConfigApiController::class, 'smsConfigSet']);
    });
});
