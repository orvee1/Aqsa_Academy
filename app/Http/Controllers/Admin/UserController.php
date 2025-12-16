<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query()
            ->with('role:id,name')
            ->when($request->filled('q'), function($qq) use ($request){
                $k = "%{$request->q}%";
                $qq->where('name','like',$k)
                   ->orWhere('email','like',$k)
                   ->orWhere('phone','like',$k);
            })
            ->when($request->filled('role_id'), fn($qq)=> $qq->where('role_id', $request->integer('role_id')))
            ->when($request->filled('super'), function($qq) use ($request){
                if ($request->super === '1') $qq->where('is_super_admin', 1);
                if ($request->super === '0') $qq->where('is_super_admin', 0);
            })
            ->latest('id');

        $users = $q->paginate(15)->appends(request()->query());

        $roles = Role::orderBy('name')->get(['id','name']);

        return view('admin.users.index', compact('users','roles'));
    }

    public function create()
    {
        $roles = Role::active()->orderBy('name')->get(['id','name']);
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_super_admin' => $request->boolean('is_super_admin'),
            'role_id' => $request->boolean('is_super_admin') ? null : $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::active()->orderBy('name')->get(['id','name']);
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_super_admin' => $request->boolean('is_super_admin'),
            'role_id' => $request->boolean('is_super_admin') ? null : $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is_super_admin) {
            return back()->with('error', "Super admin can't be deleted.");
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleSuper(User $user)
    {
        if (auth()->id() === $user->id()) {
            return back()->with('error', "You can't change your own super admin flag.");
        }

        $user->update([
            'is_super_admin' => !$user->is_super_admin,
            'role_id' => !$user->is_super_admin ? null : $user->role_id,
        ]);

        return back()->with('success', 'User access updated.');
    }
}
