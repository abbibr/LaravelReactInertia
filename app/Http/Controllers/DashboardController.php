<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        $totalPendingTasks = Task::where('status', 'pending')
            ->count();
        $myPendingTasks = Task::where('user_id', auth()->user()->id)
            ->where('status', 'pending')
            ->count();

        $totalProgressTasks = Task::where('status', 'in_progress')
            ->count();
        $myProgressTasks = Task::where('user_id', auth()->user()->id)
            ->where('status', 'in_progress')
            ->count();

        $totalCompletedTasks = Task::where('status', 'completed')
            ->count();
        $myCompletedTasks = Task::where('user_id', auth()->user()->id)
            ->where('status', 'completed')
            ->count();

        $activeTasks = Task::whereIn('status', ['pending', 'in_progress'])
            ->where('user_id', auth()->user()->id)
            ->limit(10)
            ->get();
        $activeTasks = TaskResource::collection($activeTasks);

        return Inertia::render('Dashboard', [
            'totalPendingTasks' => $totalPendingTasks,
            'myPendingTasks' => $myPendingTasks,
            'myProgressTasks' => $myProgressTasks,
            'totalProgressTasks' => $totalProgressTasks,
            'myCompletedTasks' => $myCompletedTasks,
            'totalCompletedTasks' => $totalCompletedTasks,
            'activeTasks' => $activeTasks
        ]);
    }
}
