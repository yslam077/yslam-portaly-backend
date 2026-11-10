<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\BannerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('locale')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->middleware(['throttle:10,1']);
        Route::post('send-otp', [AuthController::class, 'sendOtp'])->middleware(['throttle:10,1']);
        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    });

    Route::get('articles/popular', [ArticleController::class, 'popular']);
    Route::get('articles/trending', [ArticleController::class, 'trending']);
    Route::get('articles/featured', [ArticleController::class, 'featured']);
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{slug}', [ArticleController::class, 'show']);
    Route::get('articles/{slug}/comments', [ArticleController::class, 'comments']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{slug}/articles', [CategoryController::class, 'articles']);
    Route::get('categories/{slug}/popular', [CategoryController::class, 'popularArticles']);

    Route::get('tags', [TagController::class, 'index']);
    Route::get('banners', [BannerController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        
        Route::get('me', [ProfileController::class, 'show']);
        Route::put('me', [ProfileController::class, 'update']);

        // Comments store with throttle
        Route::post('articles/{slug}/comments', [ArticleController::class, 'storeComment'])->middleware('throttle:5,1');
    });
});