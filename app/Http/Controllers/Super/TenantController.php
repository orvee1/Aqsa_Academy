<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::latest()->paginate(20);

        return view('super.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('super.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:100', 'alpha_dash', Rule::unique('tenants','slug')],
            'domain' => ['nullable','string','max:255', Rule::unique('tenants','domain')],
            'status' => ['required','integer','in:0,1'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $tenant = Tenant::create($data);

        $tenant->institute()->create([
            'tenant_id' => $tenant->id,
            'name' => $tenant->name,
        ]);

        return redirect()->route('super.tenants.index')->with('success', 'Tenant created.');
    }

    public function edit(Tenant $tenant)
    {
        return view('super.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['required','string','max:100','alpha_dash', Rule::unique('tenants','slug')->ignore($tenant->id)],
            'domain' => ['nullable','string','max:255', Rule::unique('tenants','domain')->ignore($tenant->id)],
            'status' => ['required','integer','in:0,1'],
        ]);

        $tenant->update($data);

        return back()->with('success', 'Tenant updated.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return back()->with('success', 'Tenant deleted.');
    }
}
