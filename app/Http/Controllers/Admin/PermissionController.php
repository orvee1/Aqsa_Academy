<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $q = Permission::query()
            ->when($request->filled('q'), function($qq) use ($request){
                $k = "%{$request->q}%";
                $qq->where('key','like',$k)
                   ->orWhere('label','like',$k)
                   ->orWhere('group','like',$k);
            })
            ->when($request->filled('group'), fn($qq)=> $qq->where('group', $request->group))
            ->when($request->filled('status'), function($qq) use ($request){
                if ($request->status === '1') $qq->where('status', 1);
                if ($request->status === '0') $qq->where('status', 0);
            })
            ->orderBy('group')
            ->orderBy('key');

        // Laravel-agnostic safe:
        $permissions = $q->paginate(15)->appends(request()->query());

        $groups = Permission::query()->whereNotNull('group')->distinct()->orderBy('group')->pluck('group');

        return view('admin.permissions.index', compact('permissions','groups'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(PermissionRequest $request)
    {
        Permission::create([
            'key' => trim($request->key),
            'label' => $request->label,
            'group' => $request->group,
            'status' => $request->boolean('status'),
        ]);

        return redirect()->route('admin.permissions.index')->with('success','Permission created.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->update([
            'key' => trim($request->key),
            'label' => $request->label,
            'group' => $request->group,
            'status' => $request->boolean('status'),
        ]);

        return redirect()->route('admin.permissions.index')->with('success','Permission updated.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back()->with('success','Permission deleted.');
    }

    public function toggle(Permission $permission)
    {
        $permission->update(['status' => !$permission->status]);
        return back()->with('success','Permission status updated.');
    }
}
