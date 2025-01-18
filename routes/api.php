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
    Route::prefix('tasks')->group(function() {
        Route::get('/', [ApiController::class, 'tasksIndex'])->name('tasks.index');
        Route::post('/store', [ApiController::class, 'taskStore'])->name('tasks.store');
        Route::get('/show/{id}', [ApiController::class, 'taskShow'])->name('tasks.show');
        Route::put('/update/{task}', [ApiController::class, 'taskUpdate'])->name('tasks.update');
        Route::delete('/delete/{task}', [ApiController::class, 'taskDelete'])->name('tasks.delete');
    });
});
