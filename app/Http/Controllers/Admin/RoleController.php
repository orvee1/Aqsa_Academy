<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $q = Role::query()
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('name', 'like', $k)
                   ->orWhere('slug', 'like', $k);
            })
            ->when($request->filled('status'), function ($qq) use ($request) {
                if ($request->status === '1') $qq->where('status', 1);
                if ($request->status === '0') $qq->where('status', 0);
            })
            ->orderBy('name');

        $roles = $q->paginate(15)->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(RoleRequest $request)
    {
        Role::create([
            'name'   => $request->name,
            'slug'   => $request->slug,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update([
            'name'   => $request->name,
            'slug'   => $request->slug,
            'status' => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    public function toggle(Role $role)
    {
        $role->update(['status' => !$role->status]);

        return back()->with('success', 'Role status updated.');
    }
}
