<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

//роут для теста формы логин
Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
