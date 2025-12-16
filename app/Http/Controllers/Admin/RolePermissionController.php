<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function edit(Role $role)
    {
        $permissions = Permission::query()
            ->orderBy('group')
            ->orderBy('key')
            ->get(['id','key','label','group','status']);

        $grouped = $permissions->groupBy(fn($p) => $p->group ?: 'General');

        $assignedIds = $role->permissions()->pluck('permissions.id')->toArray();

        return view('admin.roles.permissions', compact('role','grouped','assignedIds'));
    }

    public function update(Request $request, Role $role)
    {
        $ids = collect($request->input('permission_ids', []))
            ->filter(fn($v)=> is_numeric($v))
            ->map(fn($v)=> (int)$v)
            ->unique()
            ->values()
            ->all();

        // sync pivot
        $role->permissions()->sync($ids);

        return redirect()->route('admin.roles.index')->with('success', 'Role permissions updated.');
    }
}
