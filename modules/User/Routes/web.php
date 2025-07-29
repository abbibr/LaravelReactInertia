<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Modules\User\Http\Controllers\UserController;


Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/user/test', [UserController::class, 'test']);
    Route::resource('user', UserController::class);
});
