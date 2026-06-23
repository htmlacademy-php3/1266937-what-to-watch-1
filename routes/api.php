<?php

use Illuminate\Http\Request;
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
    Route::post('/logout', 'logout');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/user', 'show');
    Route::patch('/user', 'update');
});

Route::controller(FilmController::class)->group(function () {
    Route::get('/films', 'index');
    Route::post('/films', 'store');
    Route::get('/films/{id}', 'show');
    Route::patch('/films/{id}', 'update');
    Route::get('/films/{id}/similar', 'similar');
    Route::get('/promo', 'showPromo');
    Route::post('/promo/{id}', 'setPromo');
});

Route::controller(GenreController::class)->group(function () {
    Route::get('/genres', 'index');
    Route::patch('/genres/{genre}', 'update');
});

Route::controller(FavoriteController::class)->group(function () {
    Route::get('/favorite', 'index');
    Route::post('/films/{id}/favorite/', 'store');
    Route::delete('/films/{id}/favorite/', 'destroy');
});

Route::controller(CommentController::class)->group(function () {
    Route::get('/comments/{id}', 'index');
    Route::post('/comments/{id}', 'store');
    Route::patch('/comments/{comment}', 'update');
    Route::delete('/comments/{comment}', 'destroy');
});
