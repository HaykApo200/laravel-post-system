<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Post\PostController;
use App\Jobs\TestLogJob;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [AuthController::class, 'register'] )->name('register');

Route::post('/login', [AuthController::class, 'login'] )->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'] )->name('logout');

    Route::post('/post', [PostController::class, 'uploadMedia'])->name('uploadMedia');
    Route::post('/comment/{post}', [PostController::class, 'commentOnPost'])->name('commentOnPost');
    Route::post('/like/{post}', [PostController::class, 'likeOnPost'])->name('likeOnPost');
    Route::delete('/post/{post}', [PostController::class, 'deletePost'])->name('deletePost');
});
