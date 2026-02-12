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
        Route::get('/articles', [Controllers\ArticleController::class, 'index']);
        Route::get('/articles/{id}', [Controllers\ArticleController::class, 'show']);
        Route::post('/articles', [Controllers\ArticleController::class, 'store']);
        Route::put('/articles/{id}', [Controllers\ArticleController::class, 'update']);
        Route::delete('/articles/{id}', [Controllers\ArticleController::class, 'destroy']);

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
