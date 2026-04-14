<?php

use Illuminate\Support\Facades\Route;
use Modules\Blogs\Http\Controllers\BlogsController;
use Modules\Blogs\Http\Controllers\Api\BlogsApiController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'admin/blogs', 'middleware' => 'api'], function() {
        Route::post('/list-category', [BlogsController::class, 'index'])->name('blogs.admin.categories.index');
        Route::post('/save-category', [BlogsController::class, 'store'])->name('blogs.admin.categories.store');
        Route::post('/delete-category', [BlogsController::class, 'destroy'])->name('blogs.admin.categories.destroy');
        Route::post('/change-category-status', [BlogsController::class, 'categoryStatusChange'])->name('blogs.admin.categories.status');
        Route::post('/get-category', [BlogsController::class, 'getCategory'])->name('blogs.admin.categories.active');
        Route::post('/check-unique-category-name', [BlogsController::class, 'checkUniqueCategoryName'])->name('blogs.admin.categories.unique-name');
        Route::post('/check-unique-category-slug', [BlogsController::class, 'checkUniqueCategorySlug'])->name('blogs.admin.categories.unique-slug');
        Route::post('/get-blog-category', [BlogsController::class, 'getBlogCategory'])->name('blogs.admin.categories.show');

        Route::post('/list-post', [BlogsController::class, 'listPost'])->name('blogs.admin.posts.index');
        Route::post('/save-post', [BlogsController::class, 'savePost'])->name('blogs.admin.posts.store');
        Route::post('/delete-post', [BlogsController::class, 'deletePost'])->name('blogs.admin.posts.destroy');
        Route::post('/get-post', [BlogsController::class, 'getPost'])->name('blogs.admin.posts.show');
        Route::post('/change-post-status', [BlogsController::class, 'postStatusChange'])->name('blogs.admin.posts.status');
        Route::post('/check-unique-post-title', [BlogsController::class, 'checkUniquePostTitle'])->name('blogs.admin.posts.unique-title');
        Route::post('/check-unique-post-slug', [BlogsController::class, 'checkUniquePostSlug'])->name('blogs.admin.posts.unique-slug');

        Route::post('/create-post', [BlogsApiController::class, 'createBlogPost'])->name('blogs.admin.posts.create-tool');
        Route::post('/publish-post', [BlogsApiController::class, 'publishPost'])->name('blogs.admin.posts.publish-tool');
        Route::post('/create-category', [BlogsApiController::class, 'createCategory'])->name('blogs.admin.categories.create-tool');
    });
});

Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogsApiController::class, 'listBlogs'])->name('blogs.index');
    Route::get('/{slug}', [BlogsApiController::class, 'showBlog'])->name('blogs.show');
    Route::get('/{slug}/comments', [BlogsApiController::class, 'listBlogComments'])->name('blogs.comments.index');
    Route::post('/{slug}/comments', [BlogsApiController::class, 'createComment'])->name('blogs.comments.store');
    Route::get('/categories/list', [BlogsApiController::class, 'listCategories'])->name('blogs.categories.list');
});

Route::post('/blogs', [BlogsController::class, 'blogList'])->name('blogs.legacy.list');
Route::post('/blog-details', [BlogsController::class, 'blogDetails'])->name('blogs.legacy.details');
Route::post('/blogs/add-comment', [BlogsController::class, 'addComment'])->name('blogs.legacy.comments.store');
Route::post('/blogs/list-comments', [BlogsController::class, 'listComments'])->name('blogs.legacy.comments.index');
