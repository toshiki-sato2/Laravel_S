<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MicropostsController;
use App\Http\Controllers\UserFollowController;
use App\Http\Controllers\FavoritesController;

Route::get('/', [MicropostsController::class, 'index']);

Route::get('/dashboard', [MicropostsController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/dashboard', [MicropostsController::class, 'index'])->name('microposts.index');
    Route::get('/dashboard/search/{id}', [MicropostsController::class, 'keyword_search'])->name('microposts.search');

    
    Route::prefix("users/{id}")->group(function(){
        Route::post('follow', [UserFollowController::class, 'store'])->name('user.follow');
        Route::delete('unfollow', [UserFollowController::class, 'destroy'])->name('user.unfollow');
        Route::get('followings', [UsersController::class, 'followings'])->name('users.followings');
        Route::get('followers', [UsersController::class, 'followers'])->name('users.followers');
        Route::get('favorites', [UsersController::class, 'favorites'])->name('users.favorites');
    });

    Route::prefix('microposts/{id}')->group(function() {
        Route::post('favorites', [FavoritesController::class, 'store'])->name('favorites.favorite');
    });

    Route::resource('users', UsersController::class, ['only' => ['index', 'show', 'create', 'update']]);

    Route::put('users/{id}/update_image', [UsersController::class, 'update_image'])->name('users.update_image');
    Route::put('users/{id}/edit_profile', [UsersController::class, 'edit_profile'])->name('users.edit_profile');
    Route::resource('microposts', MicropostsController::class, ['only' => ['store', 'destroy']]);
});

require __DIR__.'/auth.php';