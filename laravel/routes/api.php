<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Post\PostController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [AuthController::class, 'register'] )->name('register');

Route::post('/login', [AuthController::class, 'login'] )->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/post', [PostController::class, 'uploadMedia']);
});
