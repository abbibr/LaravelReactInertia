<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Modules\Project\Http\Controllers\ProjectController;
use Modules\Task\Http\Controllers\TaskController;


Route::middleware(['auth', 'verified'])->group(function() {
    Route::resource('/task', TaskController::class);
    Route::get('tasks/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.myTasks');
    Route::get('/tasks/test', [TaskController::class, 'test']);
});
