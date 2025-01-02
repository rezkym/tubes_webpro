<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::when(Auth::user()->hasRole('teacher'), function ($query) {
            return $query->whereHas('studentProfile.schoolClass.teachers', function ($q) {
                $q->where('users.id', Auth::id());
            });
        })
            ->with(['roles', 'studentProfile'])
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $classes = SchoolClass::all();

        return view('users.create', compact('roles', 'classes'));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('users.show', compact('user'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        if ($request->role === 'student') {
            $user->studentProfile()->create([
                'school_class_id' => $request->class_id,
                'student_number' => $request->student_number,
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'enrollment_date' => $request->enrollment_date,
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $classes = SchoolClass::all();

        return view('users.edit', compact('user', 'roles', 'classes'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($user->getRoleNames()->first() !== $request->role) {
            $user->syncRoles($request->role);
        }

        if ($request->role === 'student') {
            $user->studentProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'school_class_id' => $request->class_id,
                    'student_number' => $request->student_number,
                    'full_name' => $request->full_name,
                    'date_of_birth' => $request->date_of_birth,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'parent_name' => $request->parent_name,
                    'parent_phone' => $request->parent_phone,
                    'enrollment_date' => $request->enrollment_date,
                ]
            );
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}