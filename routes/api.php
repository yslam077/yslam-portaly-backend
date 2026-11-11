<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('locale')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');
        Route::post('register', [AuthController::class, 'register'])->middleware('throttle:10,1');
        Route::post('send-otp', [AuthController::class, 'sendOtp'])->middleware('throttle:10,1');
        Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:20,1');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });

    // Новости: фиксированные пути ВЫШЕ {slug}
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/popular', [ArticleController::class, 'popular']);
    Route::get('articles/trending', [ArticleController::class, 'trending']);
    Route::get('articles/featured', [ArticleController::class, 'featured']);
    Route::get('articles/{slug}', [ArticleController::class, 'show']);
    Route::get('articles/{slug}/comments', [CommentController::class, 'index']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{slug}/articles', [CategoryController::class, 'articles']);
    Route::get('categories/{slug}/popular', [CategoryController::class, 'popularArticles']);

    Route::get('tags', [TagController::class, 'index']);
    Route::get('banners', [BannerController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('articles/{slug}/comments', [CommentController::class, 'store'])
            ->middleware('throttle:5,1');

        Route::get('me', [ProfileController::class, 'show']);
        Route::put('me', [ProfileController::class, 'update']);
    });
});