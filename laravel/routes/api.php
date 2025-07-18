<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [AuthController::class, 'register'] )->name('register');

Route::post('/login', [AuthController::class, 'login'] )->name('login');

