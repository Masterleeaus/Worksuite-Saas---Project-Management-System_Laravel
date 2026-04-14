<?php

use Illuminate\Support\Facades\Route;
use Modules\ServiceManagement\Http\Controllers\Api\V1\CmsSurfaceController;
use Modules\ServiceManagement\Http\Controllers\Api\V1\Customer\FavoriteServiceController;
use Modules\ServiceManagement\Http\Controllers\Api\V1\Customer\ServiceController as CustomerServiceController;
use Modules\ServiceManagement\Http\Controllers\Api\V1\Provider\ServiceController as ProviderServiceController;
use Modules\ServiceManagement\Http\Controllers\Api\V1\Provider\FAQController as ProviderFAQController;
use Modules\ServiceManagement\Http\Controllers\Api\V1\Serviceman\ServiceController as ServicemanServiceController;
use Modules\ServiceManagement\Http\Controllers\Api\V1\Admin\ServiceController as AdminServiceController;
use Modules\ServiceManagement\Http\Controllers\Api\V1\Admin\FAQController as AdminFAQController;
use Modules\ServiceManagement\Http\Controllers\Api\V1\Provider\ServiceRequestController;


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
//    Route::resource('service', 'ServiceController', ['only' => ['index', 'store', 'edit', 'update', 'show']]);
    Route::put('service/status/update', [AdminServiceController::class, 'statusUpdate']);
    Route::delete('service/delete', [AdminServiceController::class, 'destroy']);

//    Route::resource('faq', 'FAQController', ['only' => ['index', 'store', 'edit', 'update', 'show']]);
    Route::put('faq/status/update', [AdminFAQController::class, 'statusUpdate']);
    Route::delete('faq/delete', [AdminFAQController::class, 'destroy']);
});

Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Api\V1\Provider', 'middleware' => ['auth:api', 'actch:provider_app']], function () {
    Route::get('service', [ProviderServiceController::class, 'index']); // index
    Route::get('service/{id}', [ProviderServiceController::class, 'show']); // show
    Route::put('service/status/update', [ProviderServiceController::class, 'statusUpdate']);
    Route::get('service/data/search', [ProviderServiceController::class, 'search']);
    Route::get('service/review/{service_id}', [ProviderServiceController::class, 'review']);
    Route::get('service/data/sub-category-wise', [ProviderServiceController::class, 'servicesBySubcategory']);

    Route::get('service-request', [ServiceRequestController::class, 'index']);
    Route::post('service-request', [ServiceRequestController::class, 'makeRequest']);

    Route::post('review-reply', [ProviderServiceController::class, 'reviewReply']);

    Route::get('faq', [ProviderFAQController::class, 'index']); // index
});

Route::group(['prefix' => 'serviceman', 'as' => 'serviceman.', 'namespace' => 'Api\V1\Service', 'middleware' => ['auth:api']], function () {
    Route::get('service/data/sub-category-wise', [ServicemanServiceController::class, 'servicesBySubcategory']);

});

Route::group(['prefix' => 'worker', 'as' => 'worker.', 'namespace' => 'Api\V1\Worker', 'middleware' => ['auth:api']], function () {
    Route::get('service/data/sub-category-wise', [ServicemanServiceController::class, 'servicesBySubcategory']);
});

Route::group(['prefix' => 'customer', 'as' => 'customer.', 'namespace' => 'Api\V1\Customer'], function () {

    Route::group(['prefix' => 'favorite', 'as' => 'favorite.', 'middleware' => ['auth:api']], function () {
        Route::get('service-list', [FavoriteServiceController::class, 'list']);
        Route::post('service', [FavoriteServiceController::class, 'store']);
        Route::post('service-delete/{service_id}', [FavoriteServiceController::class, 'destroy']);
    });

    Route::group(['prefix' => 'service', 'as' => 'service.'], function () {
        Route::get('/', [CustomerServiceController::class, 'index'])->name('index');
        Route::post('search', [CustomerServiceController::class, 'search'])->name('search');
        Route::get('search-suggestion', [CustomerServiceController::class, 'searchSuggestions'])->name('search-suggestion');
        Route::get('search/recommended', [CustomerServiceController::class, 'searchRecommended'])->name('search.recommended');
        Route::get('popular', [CustomerServiceController::class, 'popular'])->name('popular');
        Route::get('recommended', [CustomerServiceController::class, 'recommended'])->name('recommended');
        Route::get('trending', [CustomerServiceController::class, 'trending'])->name('trending');
        Route::get('recently-viewed', [CustomerServiceController::class, 'recentlyViewed'])->middleware('auth:api')->name('recently-viewed');
        Route::get('offers', [CustomerServiceController::class, 'offers'])->name('offers');
        Route::get('detail/{slug}', [CustomerServiceController::class, 'show'])->name('detail');
        Route::get('review/{service_id}', [CustomerServiceController::class, 'review'])->name('review');
        //Route::get('sub-category/{sub_category_id}', [CustomerServiceController::class, 'servicesBySubcategory']);
        Route::get('sub-category/{slug}', [CustomerServiceController::class, 'servicesBySubcategory'])->name('sub-category');

        Route::post('area-availability', [CustomerServiceController::class, 'serviceAreaAvailability'])->name('area-availability');

        Route::group(['prefix' => 'request', 'as' => 'request.'], function () {
            Route::post('make', [CustomerServiceController::class, 'makeRequest'])->middleware('auth:api')->name('make');
            Route::get('list', [CustomerServiceController::class, 'requestList'])->middleware('auth:api')->name('list');
        });
    });

    Route::get('recently-searched-keywords', [CustomerServiceController::class, 'recentlySearchedKeywords'])->middleware('auth:api')->name('recently-searched-keywords');
    Route::get('remove-searched-keywords', [CustomerServiceController::class, 'removeSearchedKeywords'])->middleware('auth:api')->name('remove-searched-keywords');
});

Route::group(['prefix' => 'servicemanagement/cms', 'as' => 'servicemanagement.cms.', 'middleware' => ['auth:api']], function () {
    Route::get('snapshot', [CmsSurfaceController::class, 'snapshot'])->name('snapshot');
});
