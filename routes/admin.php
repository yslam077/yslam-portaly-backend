<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    // Вход (только для гостей)
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'loginWithPassword'])->name('login.submit');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Только сотрудники
    Route::middleware(['auth', 'staff'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Новости: модератор сюда не заходит
        Route::middleware('role:admin,editor,author')->group(function () {
            Route::resource('articles', ArticleController::class)->except(['show']);
        });

        Route::middleware('role:admin,editor')->group(function () {
            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('tags', TagController::class)->except(['show']);
            Route::resource('banners', BannerController::class)->except(['show']);
        });

        Route::middleware('role:admin,editor,moderator')->group(function () {
            Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
            Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
            Route::post('comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
            Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
        });

        Route::middleware('role:admin')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
        });
    });
});