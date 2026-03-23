<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserRegisterController extends Controller
{
    public function index() // For user list page
    {
        $users = User::with('roles')->latest()->paginate(15);
        return view('admin.pages.admin-register', compact('users'));
    }

    public function store(Request $request) // For adding new user
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'nullable|exists:roles,name',
            'is_active' => 'required|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active,
        ]);

        if ($request->role) {
            $user->assignRole($request->role);
        }

        return back()->with('success', 'User created successfully!');
    }

    public function update(Request $request, User $user) // For updating user details
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User updated successfully!');
    }

    public function updateRole(Request $request, User $user) // For changing role on existing user
    {
        $request->validate(['role' => 'nullable|exists:roles,name']);
        $user->syncRoles($request->role ?? []);
        return back()->with('success', 'Role updated successfully!');
    }

    public function destroy(User $user) // For deleting user
    {
        // Prevent user from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        // Prevent deletion of admin users (optional)
        if ($user->hasRole('admin')) {
            return back()->with('error', 'Admin users cannot be deleted!');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully!');
    }

    public function show(User $user) // For viewing user details
    {
        return response()->json($user->load('roles'));
    }
}
