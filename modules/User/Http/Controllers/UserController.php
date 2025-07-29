<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCrudResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Modules\User\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = User::query();
        // $users = User::paginate(10);

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        if(request("name")) {
           $query->where("name", "like", "%". request("name") ."%");
        }

        if(request("email")) {
           $query->where("email", "like", "%". request("email") ."%");
        }

        $users = $query->orderBy($sortField, $sortDirection)
                ->paginate(10)
                ->onEachSide(1);

        return Inertia::render('User/Index', [
            'users' => UserCrudResource::collection($users),
            'queryParams' => request()->query() ?: null,
            'success' => session('success')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['email_verified_at'] = time();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('user.index')
            ->with('success', 'User Successfully Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('User/Edit', [
            'user' => new UserCrudResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $data['email_verified_at'] = time();
        $password = $data['password'] ?? null;

        if($password) {
            $data['password'] = Hash::make($data['password']);
        }
        else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')
            ->with('success', "User $user->name Successfully Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();

        return redirect()->route('user.index')
            ->with('success', value: "Project $name was Deleted");
    }

    public function test()
    {
        return view('user::user');
    }
}
