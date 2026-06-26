<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    private array $guards = ['admins', 'super_admins', 'users'];

    // ── Permissions ──────────────────────────────────────────────────────────

    public function permissions()
    {
        $permissions = Permission::orderBy('guard_name')->orderBy('name')->get();
        return Inertia::render('SuperAdmin/Permissions', [
            'permissions' => $permissions,
            'guards'      => $this->guards,
        ]);
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'guard_name' => 'required|in:admins,super_admins,users',
        ]);

        if (Permission::where('name', $request->name)->where('guard_name', $request->guard_name)->exists()) {
            return back()->withErrors(['name' => 'This permission already exists for the selected guard.'])->withInput();
        }

        Permission::create(['name' => $request->name, 'guard_name' => $request->guard_name]);
        return back()->with('success', 'Permission created successfully.');
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->delete();
        return back()->with('success', 'Permission deleted.');
    }

    // ── Roles ────────────────────────────────────────────────────────────────

    public function roles()
    {
        $roles       = Role::with('permissions')->orderBy('guard_name')->orderBy('name')->get();
        $permissions = Permission::orderBy('guard_name')->orderBy('name')->get();
        return Inertia::render('SuperAdmin/Roles', [
            'roles'       => $roles,
            'permissions' => $permissions,
            'guards'      => $this->guards,
        ]);
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'guard_name'      => 'required|in:admins,super_admins,users',
            'permissions'     => 'nullable|array',
            'permissions.*'   => 'integer|exists:permissions,id',
        ]);

        if (Role::where('name', $request->name)->where('guard_name', $request->guard_name)->exists()) {
            return back()->withErrors(['name' => 'Role already exists for the selected guard.'])->withInput();
        }

        $role = Role::create(['name' => $request->name, 'guard_name' => $request->guard_name]);

        if ($request->filled('permissions')) {
            $perms = Permission::whereIn('id', $request->permissions)
                               ->where('guard_name', $request->guard_name)
                               ->get();
            $role->syncPermissions($perms);
        }

        return back()->with('success', 'Role created successfully.');
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        if (Role::where('name', $request->name)
                ->where('guard_name', $role->guard_name)
                ->where('id', '!=', $role->id)
                ->exists()) {
            return back()->withErrors(['name' => 'Role name already taken for this guard.'])->withInput();
        }

        $role->update(['name' => $request->name]);

        $perms = Permission::whereIn('id', $request->permissions ?? [])
                           ->where('guard_name', $role->guard_name)
                           ->get();
        $role->syncPermissions($perms);

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroyRole(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Role deleted.');
    }
}
