<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="List all tasks",
     *     description="Retrieve a list of tasks",
     *     tags={"Tasks"},
     *     @OA\Response(response=200, description="Success"),
     *     security={{"BearerToken":{}}}
     * )
     */

    public function tasksIndex()
    {
        $tasks = Task::all();

        return response()->json([
            'tasks' => $tasks
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/tasks/store",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"BearerToken":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Task Name"),
     *             @OA\Property(property="description", type="string", example="Task Content")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Task created successfully")
     * )
     */

     public function taskStore(Request $request) {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'pending',
            'priority' => 'low',
            'due_date' => now()->addMonths(2),
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'project_id' => 5
        ]);

        return response()->json([
            'message' => 'Task created successfully'
        ]);
     }
}
