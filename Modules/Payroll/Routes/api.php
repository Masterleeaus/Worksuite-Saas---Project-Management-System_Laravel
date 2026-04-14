<?php

use Froiden\RestAPI\Facades\ApiRoute;
use Modules\Payroll\Http\Controllers\API\PayrollApiController;

ApiRoute::group(['middleware' => ['auth:sanctum', 'api.auth']], function () {
    ApiRoute::resource('payroll', PayrollApiController::class);
    ApiRoute::get('payroll-cycle', ['as' => 'api.payroll.cycle', 'uses' => [PayrollApiController::class, 'getCycle']]);
    ApiRoute::get('payroll/cycle-data/{payrollCycleId}/{year}', ['as' => 'api.payroll.cycle-data', 'uses' => [PayrollApiController::class, 'getCycleData']]);
});
