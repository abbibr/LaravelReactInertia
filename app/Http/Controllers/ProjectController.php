<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Project::query();
        // $projects = Project::paginate(10);

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        if(request("name")) {
           $query->where("name", "like", "%". request("name") ."%");
        }

        if(request("status")) {
            $query->where("status", request("status"));
        }

        $projects = $query->orderBy($sortField, $sortDirection)
                ->paginate(10)
                ->onEachSide(1);

        return Inertia::render('Project/Index', [
            'projects' => ProjectResource::collection($projects),
            'queryParams' => request()->query() ?: null,
            'success' => session('success')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Project/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $image = $data['image'] ?? null;
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        if($image) {
            $data['image_path'] = $image->store('project/images/'.Str::random());
        }

        Project::create($data);

        return redirect()->route('project.index')
            ->with('success', 'Project Successfully Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $query = $project->tasks();

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

        
        return Inertia::render('Project/Show', [
            'project' => new ProjectResource($project),
            'tasks' => TaskResource::collection($tasks),
            'queryParams' => request()->query() ?: null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return Inertia::render('Project/Edit', [
            'project' => new ProjectResource($project)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $image = $data['image'] ?? null;
        $data['updated_by'] = auth()->user()->id;

        if($image) {
            if($project->image_path) {
                Storage::disk('public')->deleteDirectory(dirname($project->image_path));
            }

            $data['image_path'] = $image->store('project/images/'.Str::random());
        }

        $project->update($data);

        return redirect()->route('project.index')
            ->with('success', "Project $project->name Successfully Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $name = $project->name;
        $project->delete();
        
        if($project->image_path) {
            Storage::disk('public')->deleteDirectory(dirname($project->image_path));
        }

        return redirect()->route('project.index')
            ->with('success', value: "Project $name was Deleted");
    }
}
