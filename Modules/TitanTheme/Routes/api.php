<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes – TitanTheme
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['api', 'auth:api'], 'prefix' => 'titan-theme', 'as' => 'api.titantheme.'], function () {
    // CSS variables (for SPA/headless usage)
    Route::get('/css-variables', [\Modules\TitanTheme\Http\Controllers\ThemeController::class, 'cssVariables'])
        ->name('css-variables');
});
