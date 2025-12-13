<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteProfileController extends Controller
{
    public function edit(Request $request)
    {
        $tenant = app('tenant');

        $institute = $tenant->institute ?: Institute::create([
            'tenant_id' => $tenant->id,
            'name' => $tenant->name,
        ]);

        return view('admin.institute.edit', compact('tenant', 'institute'));
    }

    public function update(Request $request)
    {
        $tenant = app('tenant');
        $institute = $tenant->institute;

        abort_unless($institute, 404);

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slogan' => ['nullable','string','max:255'],
            'address' => ['nullable','string','max:255'],
            'eiin' => ['nullable','string','max:50'],
            'school_code' => ['nullable','string','max:50'],
            'college_code' => ['nullable','string','max:50'],
            'phone_1' => ['nullable','string','max:50'],
            'phone_2' => ['nullable','string','max:50'],
            'mobile_1' => ['nullable','string','max:50'],
            'mobile_2' => ['nullable','string','max:50'],
            'link_1' => ['nullable','url','max:255'],
            'link_2' => ['nullable','url','max:255'],
            'link_3' => ['nullable','url','max:255'],
        ]);

        $institute->update($data);

        return back()->with('success', 'প্রতিষ্ঠানের তথ্য আপডেট হয়েছে।');
    }
}
