
<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web','auth'])
    ->prefix('account')
    ->group(function () {

        Route::view('/titan-zero', 'titanzero::dashboard')->name('titanzero.dashboard');
        Route::view('/titan-assist', 'titanzero::assist')->name('titanzero.assist');

    });
