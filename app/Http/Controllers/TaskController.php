<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Task::query();
        // $tasks = Task::paginate(10);

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        if(request("name")) {
           $query->where("name", "like", "%". request("name") ."%");
        }

        if(request("status")) {
            $query->where("status", request("status"));
        }

        $tasks = $query->orderBy($sortField, $sortDirection)
                ->paginate(10)
                ->onEachSide(1);

        return Inertia::render('Tasks/Index', [
            'tasks' => TaskResource::collection($tasks),
            'queryParams' => request()->query() ?: null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $image = $data['image'] ?? null;
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        if($image) {
            $data['image_path'] = $image->store('task/images/'.Str::random());
        }

        Task::create($data);

        return redirect()->route('task.index')
            ->with('success', 'Task Successfully Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $query = $task->tasks();

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        if(request("name")) {
           $query->where("name", "like", "%". request("name") ."%");
        }

        if(request("status")) {
            $query->where("status", request("status"));
        }

        $tasks = $query->orderBy($sortField, $sortDirection)
                ->paginate(10)
                ->onEachSide(1);

        
        return Inertia::render('Task/Show', [
            'task' => new TaskResource($task),
            'tasks' => TaskResource::collection($tasks),
            'queryParams' => request()->query() ?: null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return Inertia::render("Tasks/Edit", [
            'task' => new TaskResource($task)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();
        $image = $data['image'] ?? null;
        $data['updated_by'] = auth()->user()->id;

        if($image) {
            if($task->image_path) {
                Storage::disk('public')->deleteDirectory(dirname($task->image_path));
            }

            $data['image_path'] = $image->store('task/images/'.Str::random());
        }

        $task->update($data);

        return redirect()->route('task.index')
            ->with('success', "Task $task->name Successfully Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        if($task->image_path) {
            Storage::disk('public')->deleteDirectory(dirname($task->image_path));
        }

        return redirect()->route('task.index')
            ->with('success', "Task $task->name Successfully Deleted");
    }
}
