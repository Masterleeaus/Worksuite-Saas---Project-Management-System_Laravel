<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'accountings/settings'], function () {
    Route::get('/', 'AccSettingController@index')->name('accountings.settings.legacy.index');
    Route::post('/', 'AccSettingController@store')->name('accountings.settings.legacy.store');
});
