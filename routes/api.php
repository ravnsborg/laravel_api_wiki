<?php

use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//    return $request->user();
// })->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Login Route
|--------------------------------------------------------------------------
 */

Route::post('/login', [Controllers\AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
 */
Route::middleware('auth:api')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | V1 Routes
    |--------------------------------------------------------------------------
     */
    Route::prefix('v1')->group(static function () {

        /*
        |--------------------------------------------------------------------------
        | Article Routes
        |--------------------------------------------------------------------------
         */
        Route::get('/articles', [Controllers\ArticleController::class, 'index'])->name('index_article');
        Route::get('/articles/{id}', [Controllers\ArticleController::class, 'show'])->name('show_article');
        Route::post('/articles', [Controllers\ArticleController::class, 'store'])->name('store_article');
        Route::put('/articles/{id}', [Controllers\ArticleController::class, 'update'])->name('update_article');
        Route::delete('/articles/{id}', [Controllers\ArticleController::class, 'destroy'])->name('destroy_article');

        /*
        |--------------------------------------------------------------------------
        | Category Routes
        |--------------------------------------------------------------------------
         */
        Route::get('/categories', [Controllers\CategoryController::class, 'index']);
        Route::get('/categories/{id}', [Controllers\CategoryController::class, 'show']);
        Route::post('/categories', [Controllers\CategoryController::class, 'store']);
        Route::put('/categories/{id}', [Controllers\CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [Controllers\CategoryController::class, 'destroy']);
    });
});
