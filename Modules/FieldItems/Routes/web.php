<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Modules\FieldItems\Http\Controllers\ItemsController;
use Modules\FieldItems\Http\Controllers\ItemFileController;
use Modules\FieldItems\Http\Controllers\ItemCategoryController;
use Modules\FieldItems\Http\Controllers\ItemSubCategoryController;
use Modules\FieldItems\Http\Controllers\ItemPricingController;
use Modules\FieldItems\Http\Controllers\TaskItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group.
|
*/

Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    Route::post('items/apply-quick-action', [ProductController::class, 'applyQuickAction'])
        ->name('fielditems.apply_quick_action');

    // Item pricing preview — must be declared before the resource route to avoid route conflicts
    Route::get('items/pricing/preview', [ItemPricingController::class, 'preview'])
        ->name('fielditems.pricing.preview');

    Route::resource('items', ItemsController::class);
    Route::resource('itemCategory', ItemCategoryController::class);
    Route::get('getItemSubCategories/{id}', [ItemSubCategoryController::class, 'getSubCategories'])
        ->name('get_item_sub_categories');
    Route::resource('itemSubCategory', ItemSubCategoryController::class);

    Route::get('item-files/download/{id}', [ItemFileController::class, 'download'])
        ->name('item-files.download');
    Route::post('item-files/delete-image/{id}', [ItemFileController::class, 'deleteImage'])
        ->name('item-files.delete_image');
    Route::post('item-files/update-images', [ItemFileController::class, 'updateImages'])
        ->name('item-files.update_images');
    Route::resource('item-files', ItemFileController::class);

    // FSM job consumption: items used on a task/booking
    Route::get('tasks/{taskId}/task-items', [TaskItemController::class, 'index'])
        ->name('task-items.index');
    Route::post('task-items', [TaskItemController::class, 'store'])
        ->name('task-items.store');
    Route::put('task-items/{id}', [TaskItemController::class, 'update'])
        ->name('task-items.update');
    Route::delete('task-items/{id}', [TaskItemController::class, 'destroy'])
        ->name('task-items.destroy');
});
