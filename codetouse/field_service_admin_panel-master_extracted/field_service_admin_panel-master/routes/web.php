<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth','role:admin'])->name('home');




Route::middleware(["auth","role:admin"])->group(
    function () {
      
        Route::prefix("/products")->group(
            function () {
                Route::get('/index/{type}/{table}/{id}', [App\Http\Controllers\Web\v1\ProductsController::class, 'index'])->name('products.index');
                Route::get('/any-data/{type}/{table}/{id}', [App\Http\Controllers\Web\v1\ProductsController::class, 'any_data'])->name('products.anyData');
                Route::get('/show/{id}', [App\Http\Controllers\Web\v1\ProductsController::class, 'show'])->name('products.show');
                Route::get('/remove/{id}/{type}/{token}', [App\Http\Controllers\Web\v1\ProductsController::class, 'remove'])->name('products.remove');
                Route::get('/update-status/{id}/{status}/{type}/{token}', [App\Http\Controllers\Web\v1\ProductsController::class, 'updateProductStatus'])->name('products.updateStatus');
                Route::post('/update-affiliate-with-approve', [App\Http\Controllers\Web\v1\ProductsController::class, 'updateAffiliateWithApprove'])->name('products.approvePlusUpdateAffiliate');
                Route::post('/store', [App\Http\Controllers\Web\v1\ProductsController::class, 'store'])->name('products.store');
                Route::post('/updation', [App\Http\Controllers\Web\v1\ProductsController::class, 'updation'])->name('products.update');
                Route::get('/update/{id}', [App\Http\Controllers\Web\v1\ProductsController::class, 'update']);
    
                Route::get('/create', [App\Http\Controllers\Web\v1\ProductsController::class, 'create'])->name('products.create');
    
            }
        );

        Route::prefix("/parent-brands")->group(
            function () {
                Route::get('/index', [App\Http\Controllers\Web\v1\ParentBrandsController::class, 'index'])->name("parent_brands.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\ParentBrandsController::class, 'any_data'])->name("parent_brands.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\ParentBrandsController::class, 'remove']);
                Route::post('/add', [App\Http\Controllers\Web\v1\ParentBrandsController::class, 'add'])->name("parent_brands.add");
                Route::post('/update', [App\Http\Controllers\Web\v1\ParentBrandsController::class, 'update'])->name("parent_brands.update");
             }
        ); 
        Route::prefix("/job-types")->group(
            function () {
                Route::get('/index', [App\Http\Controllers\Web\v1\JobTypesController::class, 'index'])->name("job_types.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\JobTypesController::class, 'any_data'])->name("job_types.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\JobTypesController::class, 'remove']);
                Route::post('/add', [App\Http\Controllers\Web\v1\JobTypesController::class, 'add'])->name("job_types.add");
                Route::post('/update', [App\Http\Controllers\Web\v1\JobTypesController::class, 'update'])->name("job_types.update");
             }
        ); 
        Route::prefix("/brands")->group(
            function () {
                Route::get('/index', [App\Http\Controllers\Web\v1\BrandsController::class, 'index'])->name("brands.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\BrandsController::class, 'any_data'])->name("brands.anyData");
                Route::get('/remove', [App\Http\Controllers\Web\v1\BrandsController::class, 'remove']);
                Route::post('/add', [App\Http\Controllers\Web\v1\BrandsController::class, 'add'])->name("brands.add");
                Route::post('/update', [App\Http\Controllers\Web\v1\BrandsController::class, 'update'])->name("brands.update");
             }
        ); 
        Route::prefix("/uom-types")->group(
            function () {
                Route::get('/index', [App\Http\Controllers\Web\v1\UomTypesController::class, 'index'])->name("uom_types.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\UomTypesController::class, 'any_data'])->name("uom_types.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\UomTypesController::class, 'remove']);
                Route::post('/add', [App\Http\Controllers\Web\v1\UomTypesController::class, 'add'])->name("uom_types.add");
                Route::post('/update', [App\Http\Controllers\Web\v1\UomTypesController::class, 'update'])->name("uom_types.update");
             }
        ); 
        Route::prefix("/uoms")->group(
            function () {
                Route::get('/index', [App\Http\Controllers\Web\v1\UomsController::class, 'index'])->name("uoms.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\UomsController::class, 'any_data'])->name("uoms.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\UomsController::class, 'remove']);
                Route::post('/add', [App\Http\Controllers\Web\v1\UomsController::class, 'add'])->name("uoms.add");
                Route::post('/update', [App\Http\Controllers\Web\v1\UomsController::class, 'update'])->name("uoms.update");
             }
        ); 
        Route::prefix("/major-categories")->group(
            function () {
                Route::get('/index', [App\Http\Controllers\Web\v1\MajorCategoriesController::class, 'index'])->name("major_categories.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\MajorCategoriesController::class, 'any_data'])->name("major_categories.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\MajorCategoriesController::class, 'remove']);
                Route::post('/add', [App\Http\Controllers\Web\v1\MajorCategoriesController::class, 'add'])->name("major_categories.add");
                Route::post('/update', [App\Http\Controllers\Web\v1\MajorCategoriesController::class, 'update'])->name("major_categories.update");
             }
        );
        Route::prefix("/sub-major-categories")->group(
            function () {
                Route::get('/index', [App\Http\Controllers\Web\v1\SubMajorCategoriesController::class, 'index'])->name("sub_major_categories.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\SubMajorCategoriesController::class, 'any_data'])->name("sub_major_categories.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\SubMajorCategoriesController::class, 'remove']);
                Route::post('/add', [App\Http\Controllers\Web\v1\SubMajorCategoriesController::class, 'add'])->name("sub_major_categories.add");
                Route::post('/update', [App\Http\Controllers\Web\v1\SubMajorCategoriesController::class, 'update'])->name("sub_major_categories.update");
             }
        ); 
        Route::prefix("/sub-categories")->group(
            function () {
                Route::get('/index', [App\Http\Controllers\Web\v1\SubCategoriesController::class, 'index'])->name("sub_categories.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\SubCategoriesController::class, 'any_data'])->name("sub_categories.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\SubCategoriesController::class, 'remove']);
                Route::post('/add', [App\Http\Controllers\Web\v1\SubCategoriesController::class, 'add'])->name("sub_categories.add");
                Route::post('/update', [App\Http\Controllers\Web\v1\SubCategoriesController::class, 'update'])->name("sub_categories.update");
             }
        ); 
        Route::prefix("/contact-by-persons")->group(
            function () {
                Route::get('/index', [App\Http\Controllers\Web\v1\ContactByPersonsController::class, 'index'])->name("contact_by_persons.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\ContactByPersonsController::class, 'any_data'])->name("contact_by_persons.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\ContactByPersonsController::class, 'remove']);
                Route::post('/add', [App\Http\Controllers\Web\v1\ContactByPersonsController::class, 'add'])->name("contact_by_persons.add");
                Route::post('/update', [App\Http\Controllers\Web\v1\ContactByPersonsController::class, 'update'])->name("contact_by_persons.update");
             }
        ); 
        Route::prefix("/sub-admins")->group(
            function () {
              
                Route::get('/index', [App\Http\Controllers\Web\v1\SubAdminsController::class, 'index'])->name("sub_admins.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\SubAdminsController::class, 'any_data'])->name("sub_admins.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\SubAdminsController::class, 'remove']);
                Route::get('/create', [App\Http\Controllers\Web\v1\SubAdminsController::class, 'create'])->name("sub_admins.create");
                Route::post('/store', [App\Http\Controllers\Web\v1\SubAdminsController::class, 'store'])->name("sub_admins.store");
                Route::post('/update', [App\Http\Controllers\Web\v1\SubAdminsController::class, 'update'])->name("sub_admins.update");
                Route::get('/request-update/{id}', [App\Http\Controllers\Web\v1\SubAdminsController::class, 'request_update'])->name("sub_admins.request_update");
            
            }
        );
        Route::prefix("/admins")->group(
            function () {
              
                Route::get('/index', [App\Http\Controllers\Web\v1\AdminsController::class, 'index'])->name("admins.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\AdminsController::class, 'any_data'])->name("admins.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\AdminsController::class, 'remove']);
                Route::get('/create', [App\Http\Controllers\Web\v1\AdminsController::class, 'create'])->name("admins.create");
                Route::post('/store', [App\Http\Controllers\Web\v1\AdminsController::class, 'store'])->name("admins.store");
                Route::post('/update', [App\Http\Controllers\Web\v1\AdminsController::class, 'update'])->name("admins.update");
                Route::get('/request-update/{id}', [App\Http\Controllers\Web\v1\AdminsController::class, 'request_update'])->name("admins.request_update");
            
            }
        );

        Route::prefix("/customers")->group(
            function () {
              
                Route::get('/index', [App\Http\Controllers\Web\v1\CustomersController::class, 'index'])->name("customers.index");
                Route::get('/any-data', [App\Http\Controllers\Web\v1\CustomersController::class, 'any_data'])->name("customers.anyData");
                Route::get('/remove/{id}', [App\Http\Controllers\Web\v1\CustomersController::class, 'remove']);
                Route::get('/create', [App\Http\Controllers\Web\v1\CustomersController::class, 'create'])->name("customers.create");
                Route::post('/store', [App\Http\Controllers\Web\v1\CustomersController::class, 'store'])->name("customers.store");
                Route::post('/update', [App\Http\Controllers\Web\v1\CustomersController::class, 'update'])->name("customers.update");
                Route::get('/request-update/{id}', [App\Http\Controllers\Web\v1\CustomersController::class, 'request_update'])->name("customers.request_update");
            
            }
        );
    }
    
);
    
    





require __DIR__.'/auth.php';
