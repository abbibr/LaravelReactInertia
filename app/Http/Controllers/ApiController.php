<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;


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
}
