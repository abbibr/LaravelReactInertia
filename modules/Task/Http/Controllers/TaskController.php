<?php

namespace Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserCrudResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Modules\Project\Models\Project;
use Modules\Task\Models\Task;
use Modules\User\Models\User;

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

        // Add an `authorized` field to each task for frontend checks
        $tasks->getCollection()->transform(function ($task) {
            $task->updateTask = Gate::allows('updateTask', $task); 
            // Check if the user can update or delete
            $task->deleteTask = Gate::allows('deleteTask', $task);

            return $task;
        });

        return Inertia::render('Tasks/Index', [
            'tasks' => TaskResource::collection($tasks),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::orderBy('name', 'asc')->get();
        $users = User::all();

        return Inertia::render('Tasks/Create', [
            'projects' => ProjectResource::collection($projects),
            'users' => UserResource::collection($users),
        ]);
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
        return Inertia::render('Tasks/Show', [
            'task' => new TaskResource($task)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        if(!Gate::allows('updateTask', $task)) {
            abort(403, 'SORRY! You can not update this task...');
        }

        $projects = Project::orderBy('name', 'asc')->get();
        $users = User::all();

        return Inertia::render("Tasks/Edit", [
            'task' => new TaskResource($task),
            'projects' => ProjectResource::collection($projects),
            'users' => UserResource::collection($users)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('updateTask', $task);

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
        if(Gate::denies('deleteTask', $task)) {
            abort(403, 'SORRY! You can not delete this task...');
        }

        $task->delete();

        if($task->image_path) {
            Storage::disk('public')->deleteDirectory(dirname($task->image_path));
        }

        return redirect()->route('task.index')
            ->with('success', "Task $task->name Successfully Deleted");
    }

    public function myTasks() {
        $user = auth()->user();
        $query = Task::query()->where('user_id', $user->id);

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
            'success' => session('success')
        ]);
    }

    public function test() {
        return view('task::task');
    }
}
