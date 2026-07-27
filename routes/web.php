<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\YearsController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\WorksheetController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubgroupController;

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

Route::post('/faculties', [FacultyController::class, 'store'])->name('faculties.store')->middleware('auth');
Route::put('/faculties/{faculty}', [FacultyController::class, 'update'])->name('faculties.update')->middleware('auth');
Route::delete('/faculties/{faculty}', [FacultyController::class, 'destroy'])->name('faculties.destroy')->middleware('auth');

Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store')->middleware('auth');
Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update')->middleware('auth');
Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy')->middleware('auth');

Route::post('/worksheets', [WorksheetController::class, 'store'])->name('worksheets.store')->middleware('auth');
Route::put('/worksheets/{worksheet}', [WorksheetController::class, 'update'])->name('worksheets.update')->middleware('auth');
Route::delete('/worksheets/{worksheet}', [WorksheetController::class, 'destroy'])->name('worksheets.destroy')->middleware('auth');

Route::post('/groups', [GroupController::class, 'store'])->name('groups.store')->middleware('auth');
Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update')->middleware('auth');
Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy')->middleware('auth');

Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store')->middleware('auth');
Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update')->middleware('auth');
Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy')->middleware('auth');

Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store')->middleware('auth');

Route::post('/subgroups', [SubgroupController::class, 'store'])->name('subgroups.store')->middleware('auth');
Route::put('/subgroups/{subgroup}', [SubgroupController::class, 'update'])->name('subgroups.update')->middleware('auth');
Route::delete('/subgroups/{subgroup}', [SubgroupController::class, 'destroy'])->name('subgroups.destroy')->middleware('auth');
