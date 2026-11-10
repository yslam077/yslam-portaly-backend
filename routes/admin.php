<?php
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin,editor,author')->group(function () {
        Route::resource('articles', ArticleController::class);
    });

    Route::middleware('role:admin,editor')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('tags', TagController::class);
        Route::resource('banners', BannerController::class);
    });

    Route::middleware('role:admin,editor,moderator')->group(function () {
        Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
        Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
        Route::post('comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
        Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});