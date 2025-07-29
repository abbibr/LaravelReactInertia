<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Modules\Project\Http\Controllers\ProjectController;


Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/project/test', [ProjectController::class, 'test']);
    Route::resource('/project', ProjectController::class);
});
