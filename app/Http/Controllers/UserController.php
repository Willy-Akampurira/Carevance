<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    /**
     * Display a listing of active users with search.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage with role assignment and send activation link.
     */
    public function store(Request $request)
    {
        // ✅ Rectified validation: Admin only inputs email and assigns a Spatie role
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|string|exists:roles,name',
        ]);

        // ✅ Creates the user with a blank password and sets them to inactive
        $user = User::create([
            'name'      => null,  // Will be set by the employee during activation
            'email'     => $validated['email'],
            'password'  => null,  // Kept null until they choose their own password
            'is_active' => false, // Locked until account activation process is completed
        ]);

        // Assign Spatie Role
        $user->assignRole($validated['role']);

        // ✅ Fire Laravel's native token broker to dispatch the secure activation email
        Password::broker()->sendResetLink(['email' => $user->email]);

        return redirect()->route('users.index')->with('success', 'Staff account created successfully! An activation link has been sent to their email.');
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string|exists:roles,name',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Soft delete a user.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User moved to trash.');
    }

    /**
     * Display trashed users with search.
     */
    public function trash(Request $request)
    {
        $query = User::onlyTrashed();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        return view('users.trash', compact('users'));
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('users.trash')->with('success', 'User restored successfully.');
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('users.trash')->with('success', 'User permanently deleted.');
    }
}