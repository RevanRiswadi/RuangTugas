<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('projects', ProjectController::class)->except('show');

Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
Route::resource('tasks', TaskController::class)->except('show');
