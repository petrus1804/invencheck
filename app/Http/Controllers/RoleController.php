<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');

        return view('roles', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => str()->slug($validated['name']),
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('roles')->with('success', 'Role berhasil ditambahkan.');
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'slug' => str()->slug($validated['name']),
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('roles')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->slug, ['admin', 'staff', 'user'])) {
            return redirect()->route('roles')->with('error', 'Role bawaan sistem tidak bisa dihapus.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('roles')->with('error', 'Role tidak bisa dihapus karena masih dipakai oleh pengguna.');
        }

        $role->delete();

        return redirect()->route('roles')->with('success', 'Role berhasil dihapus.');
    }
}