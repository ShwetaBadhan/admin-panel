<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    // ROLES PAGE
    public function rolesIndex()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        $permissions = Permission::all();
        return view('admin.pages.roles', compact('roles', 'permissions'));
    }

    // PERMISSIONS PAGE
    public function permissionsIndex()
    {
        $permissions = Permission::with('roles')->get();
        return view('admin.pages.permissions', compact('permissions'));
    }

    // STORE NEW ROLE WITH PERMISSIONS
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array', // ADD THIS
            'permissions.*' => 'exists:permissions,name' // ADD THIS
        ]);
        
        // Create the role
        $role = Role::create([
            'name' => $request->name, 
            'guard_name' => 'web'
        ]);
        
        // Assign permissions if provided
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }
        
        return back()->with('success', 'Role created successfully' . 
            ($request->has('permissions') ? ' with ' . count($request->permissions) . ' permissions' : '') . '!');
    }

    // UPDATE ROLE NAME
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id
        ]);
        
        if ($role->name === 'admin') {
            return back()->with('error', 'Cannot rename admin role');
        }
        
        $role->update(['name' => $request->name]);
        
        return back()->with('success', 'Role updated successfully!');
    }

    // STORE NEW PERMISSION
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);
        
        Permission::create([
            'name' => $request->name, 
            'guard_name' => 'web'
        ]);
        
        return back()->with('success', 'Permission created successfully!');
    }

    // UPDATE PERMISSION
    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id
        ]);
        
        $permission->update(['name' => $request->name]);
        
        return back()->with('success', 'Permission updated successfully!');
    }

    // UPDATE ROLE PERMISSIONS
    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);
        
        $role->syncPermissions($request->permissions ?? []);
        return back()->with('success', 'Permissions updated for ' . $role->name . 
            ' (' . count($request->permissions ?? []) . ' permissions assigned)');
    }

    // DELETE ROLE
    public function destroyRole(Role $role)
    {
        if ($role->name === 'admin') {
            return back()->with('error', 'Cannot delete admin role');
        }
        
        $role->delete();
        return back()->with('success', 'Role deleted successfully!');
    }

    // DELETE PERMISSION
    public function destroyPermission(Permission $permission)
    {
        $permission->delete();
        return back()->with('success', 'Permission deleted successfully!');
    }
}