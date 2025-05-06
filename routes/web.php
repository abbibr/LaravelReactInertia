<?php

use App\Http\Controllers\AIInterviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;


Route::redirect('/', '/dashboard');


Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/project', ProjectController::class);
    Route::resource('/task', TaskController::class);
    Route::resource('/user', UserController::class);
    Route::get('tasks/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.myTasks');
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


Route::get('/test-openai', function () {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        'Content-Type' => 'application/json',
    ])->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => 'say my name'],
        ],
    ]);

    $data = $response->json();

    if (isset($data['choices'][0]['message']['content'])) {
        return $data['choices'][0]['message']['content'];
    }

    return response()->json([
        'error' => 'Something went wrong',
        'debug' => $data,
    ], 500);
});


Route::get('/interview', function () {
    return Inertia::render('Candidate/InterviewChat');
});
Route::post('/ai-interview/start', [AIInterviewController::class, 'start']);
Route::post('/ai-interview/answer', [AIInterviewController::class, 'answer']);

require __DIR__.'/auth.php';
