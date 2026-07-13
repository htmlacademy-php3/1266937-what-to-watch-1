<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\UserController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::controller(FilmController::class)->group(function () {
    Route::get('/films', 'index');
    Route::get('/films/{film}', 'show');
    Route::get('/films/{film}/similar', 'similar');
    Route::get('/promo', 'showPromo');
});

Route::controller(GenreController::class)->group(function () {
    Route::get('/genres', 'index');
});

Route::controller(CommentController::class)->group(function () {
    Route::get('/comments/{film}', 'index');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'show');
        Route::patch('/user', 'update');
    });

    Route::middleware('role:moderator')->group(function () {
        Route::controller(GenreController::class)->group(function () {
            Route::patch('/genres/{genre}', 'update');
        });
        Route::controller(FilmController::class)->group(function () {
            Route::post('/promo/{film}', 'setPromo');
        });
        Route::controller(FilmController::class)->group(function () {
            Route::post('/films', 'store');
            Route::patch('/films/{film}', 'update');
        });
    });

    Route::controller(FavoriteController::class)->group(function () {
        Route::get('/favorite', 'index');
        Route::post('/films/{film}/favorite/', 'store');
        Route::delete('/films/{film}/favorite/', 'destroy');
    });

    Route::controller(CommentController::class)->group(function () {
        Route::post('/comments/{film}', 'store');
        Route::patch('/comments/{comment}', 'update');
        Route::delete('/comments/{comment}', 'destroy');
    });
});
