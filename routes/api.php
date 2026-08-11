<?php

use App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Login Route
|--------------------------------------------------------------------------
 */

Route::post('/login', [Controllers\AuthController::class, 'login']);

Route::post('/register', [Controllers\AuthController::class, 'register'])->name('register');
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

        Route::get('/articles/favorites', [Controllers\ArticleController::class, 'favorites'])->name('favorite_articles');
        Route::get('/articles/search', [Controllers\ArticleController::class, 'search'])->name('search_articles');
        Route::get('/articles', [Controllers\ArticleController::class, 'index'])->name('index_article');
        Route::get('/articles/{id}', [Controllers\ArticleController::class, 'show'])->name('show_article');
        Route::post('/articles', [Controllers\ArticleController::class, 'store'])->name('store_article');
        Route::match(['put', 'patch'], '/articles/{id}', [Controllers\ArticleController::class, 'update'])->name('update_article');
        Route::delete('/articles/{id}', [Controllers\ArticleController::class, 'destroy'])->name('destroy_article');

        /*
        |--------------------------------------------------------------------------
        | Category Routes
        |--------------------------------------------------------------------------
         */
        Route::get('/categories/{id}/articles', [Controllers\CategoryController::class, 'category_articles'])->name('category_articles');
        Route::get('/categories', [Controllers\CategoryController::class, 'index'])->name('index_category');
        Route::get('/categories/{id}', [Controllers\CategoryController::class, 'show'])->name('show_category');
        Route::post('/categories', [Controllers\CategoryController::class, 'store'])->name('store_category');
        Route::put('/categories/{id}', [Controllers\CategoryController::class, 'update'])->name('update_category');
        Route::delete('/categories/{id}', [Controllers\CategoryController::class, 'destroy'])->name('destroy_category');

        /*
        |--------------------------------------------------------------------------
        | Link Routes
        |--------------------------------------------------------------------------
         */
        Route::get('/links', [Controllers\LinkController::class, 'index'])->name('index_link');
        Route::get('/links/{id}', [Controllers\LinkController::class, 'show'])->name('show_link');
        Route::post('/links', [Controllers\LinkController::class, 'store'])->name('store_link');
        Route::match(['put', 'patch'], '/links/{id}', [Controllers\LinkController::class, 'update'])->name('update_link');
        Route::delete('/links/{id}', [Controllers\LinkController::class, 'destroy'])->name('destroy_link');

        /*
        |--------------------------------------------------------------------------
        | Entities Routes
        |--------------------------------------------------------------------------
         */
        Route::get('/entities', [Controllers\EntityController::class, 'index'])->name('index_entity');
        Route::get('/entities/{id}', [Controllers\EntityController::class, 'show'])->name('show_entity');
        Route::post('/entities', [Controllers\EntityController::class, 'store'])->name('store_entity');

        /*
        |--------------------------------------------------------------------------
        | User Routes
        |--------------------------------------------------------------------------
        */
        Route::get('/users', [Controllers\UserController::class, 'index'])->name('index_user');
        Route::get('/users/{id}', [Controllers\UserController::class, 'show'])->name('show_user');
        Route::put('/users/{id}/entities', [Controllers\UserController::class, 'update_entity'])->name('update_user_entity');

        Route::get('/user', function (Request $request) {

            $user = User::with('entity')->find($request->user()->id);

            if (! $user) {
                return response()->json(
                    ['message' => 'User not found'],
                    Controller::HTTP_STATUS_CODES['not_found']
                );
            }

            return response()->json(
                ['user' => new UserResource($user)],
                Controller::HTTP_STATUS_CODES['success']
            );

            return $request->user()->id;
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Logout Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/logout', [Controllers\AuthController::class, 'logout']);
});
