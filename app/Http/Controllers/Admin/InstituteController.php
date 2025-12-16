<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstituteRequest;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    public function index(Request $request)
    {
        $q = Institute::query()
            ->when($request->filled('q'), function($qq) use ($request){
                $k = "%{$request->q}%";
                $qq->where('name','like',$k)
                   ->orWhere('eiin','like',$k)
                   ->orWhere('school_code','like',$k)
                   ->orWhere('college_code','like',$k);
            })
            ->when($request->filled('status'), function($qq) use ($request){
                if ($request->status === '1') $qq->where('status', 1);
                if ($request->status === '0') $qq->where('status', 0);
            })
            ->latest('id');

        $institutes = $q->paginate(15)->appends(request()->query());

        return view('admin.institutes.index', compact('institutes'));
    }

    public function create()
    {
        return view('admin.institutes.create');
    }

    public function store(InstituteRequest $request)
    {
        Institute::create($request->validated());

        return redirect()
            ->route('admin.institutes.index')
            ->with('success', 'Institute created successfully.');
    }

    public function edit(Institute $institute)
    {
        return view('admin.institutes.edit', compact('institute'));
    }

    public function update(InstituteRequest $request, Institute $institute)
    {
        $institute->update($request->validated());

        return redirect()
            ->route('admin.institutes.index')
            ->with('success', 'Institute updated successfully.');
    }

    public function destroy(Institute $institute)
    {
        $institute->delete();

        return back()->with('success', 'Institute deleted successfully.');
    }

    public function toggle(Institute $institute)
    {
        $institute->update(['status' => !$institute->status]);
        return back()->with('success', 'Status updated.');
    }
}
