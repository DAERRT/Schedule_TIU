<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\YearsController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\RoomController;

// Главная страница сразу перенаправляет на список годов
Route::redirect('/', '/years')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/years', [YearsController::class, 'index'])->name('years.index')->middleware('auth');
Route::get('/years/{year}', [YearsController::class, 'show'])->name('years.show')->middleware('auth');
Route::post('/years', [YearsController::class, 'store'])->name('years.store')->middleware('auth');
Route::delete('/years/{year}', [YearsController::class, 'destroy'])->name('years.destroy')->middleware('auth');

Route::post('/buildings', [BuildingController::class, 'store'])->name('buildings.store')->middleware('auth');
Route::put('/buildings/{building}', [BuildingController::class, 'update'])->name('buildings.update')->middleware('auth');
Route::delete('/buildings/{building}', [BuildingController::class, 'destroy'])->name('buildings.destroy')->middleware('auth');

Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store')->middleware('auth');
Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update')->middleware('auth');
Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy')->middleware('auth');
