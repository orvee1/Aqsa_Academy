<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('tenant')->latest()->paginate(20);
        return view('super.users.index', compact('users'));
    }

    public function create()
    {
        $tenants = Tenant::orderBy('name')->get();
        return view('super.users.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')],
            'password' => ['required','string','min:6','confirmed'],
            'tenant_id' => ['nullable','exists:tenants,id'],
            'role' => ['required','in:super-admin,tenant-admin,editor'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'tenant_id' => $data['tenant_id'],
            'is_super_admin' => $data['role'] === 'super-admin',
        ]);

        if (!$user->is_super_admin) {
            $user->assignRole($data['role']);
        }

        return redirect()->route('super.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $tenants = Tenant::orderBy('name')->get();
        return view('super.users.edit', compact('user','tenants'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:6','confirmed'],
            'tenant_id' => ['nullable','exists:tenants,id'],
            'role' => ['required','in:super-admin,tenant-admin,editor'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->tenant_id = $data['tenant_id'];
        $user->is_super_admin = $data['role'] === 'super-admin';

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if ($user->is_super_admin) {
            $user->syncRoles([]);
        } else {
            $user->syncRoles([$data['role']]);
        }

        return back()->with('success', 'User updated.');
    }
}
