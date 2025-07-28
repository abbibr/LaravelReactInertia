<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use Modules\Task\Models\Task;
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

    public function taskStore(Request $request)
    {
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



    /**
     * @OA\Get(
     *     path="/api/tasks/show/{id}",
     *     summary="Get a task",
     *     description="Retrieve a task",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, description="Task ID", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not Found"),
     *     security={{"BearerToken":{}}}
     * )
     */

    public function taskShow($id)
    {
        $task = Task::find($id);

        return response()->json([
            'task' => $task
        ]);
    }

    
     /**
     * @OA\Put(
     *     path="/api/tasks/update/{id}",
     *     summary="Update a specific task",
     *     description="Update a task by its ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, description="Task ID", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="description", type="string", example="Updated content")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Task updated successfully"),
     *     @OA\Response(response=404, description="Not Found"),
     *     security={{"BearerToken":{}}}
     * )
     */

    public function taskUpdate(Task $task, Request $request) {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $getTask = $task->update([
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
            'task' => $getTask
        ]);
    }


     /**
     * @OA\Delete(
     *     path="/api/tasks/delete/{id}",
     *     summary="Delete a specific task",
     *     description="Delete a task by its ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(name="id", in="path", required=true, description="Task ID", @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Task deleted successfully"),
     *     @OA\Response(response=404, description="Not Found"),
     *     security={{"BearerToken":{}}}
     * )
     */

    public function taskDelete(Task $task) {
        $task->delete();

        return response()->json([
            'message' => 'Task successfully deleted'
        ]);
    }
}
