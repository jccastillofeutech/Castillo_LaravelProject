<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', [PageController::class, 'about']);
Route::get('/tasks/{id}', [PageController::class, 'task']);
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

Route::delete('/members/bulk-delete', [MemberController::class, 'bulkDestroy'])->name('members.bulk-destroy');
Route::resource('members', MemberController::class);
Route::patch('/members/{member}/activate', [MemberController::class, 'activate'])->name('members.activate');
Route::delete('/members/{member}/permanently', [MemberController::class, 'permanentlyDestroy'])->name('members.permanently-destroy');
Route::resource('membership-categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['membership-categories' => 'category'])->names('categories');
Route::resource('membership-levels', LevelController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['membership-levels' => 'level'])->names('levels');
