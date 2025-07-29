<?php

use App\Http\Controllers\AIInterviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;


Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/ai-interview/start', [AIInterviewController::class, 'start']);
    Route::post('/ai-interview/continue', [AIInterviewController::class, 'continue']);
});

Route::inertia('/candidate/ai-interview', 'Candidate/InterviewChat')->name('test.test');

Route::view('all', 'all')->name('all.all');

require __DIR__.'/auth.php';
