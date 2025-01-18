<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::post('/register', [AuthController::class, 'register'])->name('user.register');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/tasks', [ApiController::class, 'tasksIndex'])->name('tasks.index');
});
