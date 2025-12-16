<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatementRequest;
use App\Models\Statement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StatementController extends Controller
{
   public function index(Request $request)
    {
        $q = Statement::query()
            ->when($request->filled('q'), function($qq) use ($request){
                $k = "%{$request->q}%";
                $qq->where('title','like',$k)
                   ->orWhere('author_name','like',$k)
                   ->orWhere('author_designation','like',$k);
            })
            ->when($request->filled('status'), function($qq) use ($request){
                if ($request->status === '1') $qq->where('status', 1);
                if ($request->status === '0') $qq->where('status', 0);
            })
            ->orderBy('position')
            ->orderByDesc('id');

        $statements = $q->paginate(15)->appends(request()->query());

        return view('admin.statements.index', compact('statements'));
    }

    public function create()
    {
        return view('admin.statements.create');
    }

    public function store(StatementRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('author_photo')) {
            $data['author_photo_path'] = $request->file('author_photo')->store('statements/authors', 'public');
        }

        Statement::create($data);

        return redirect()->route('admin.statements.index')->with('success','Statement created.');
    }

    public function edit(Statement $statement)
    {
        return view('admin.statements.edit', compact('statement'));
    }

    public function update(StatementRequest $request, Statement $statement)
    {
        $data = $request->validated();

        if ($request->hasFile('author_photo')) {
            if ($statement->author_photo_path) Storage::disk('public')->delete($statement->author_photo_path);
            $data['author_photo_path'] = $request->file('author_photo')->store('statements/authors', 'public');
        }

        $statement->update($data);

        return redirect()->route('admin.statements.index')->with('success','Statement updated.');
    }

    public function destroy(Statement $statement)
    {
        if ($statement->author_photo_path) Storage::disk('public')->delete($statement->author_photo_path);
        $statement->delete();

        return back()->with('success','Statement deleted.');
    }

    public function toggle(Statement $statement)
    {
        $statement->update(['status' => !$statement->status]);
        return back()->with('success','Status updated.');
    }

    public function up(Statement $statement)
    {
        $this->swap($statement, 'up');
        return back();
    }

    public function down(Statement $statement)
    {
        $this->swap($statement, 'down');
        return back();
    }

    private function swap(Statement $statement, string $dir): void
    {
        $base = Statement::query();

        if ($dir === 'up') {
            $neighbor = (clone $base)
                ->where('position', '<', $statement->position)
                ->orderByDesc('position')
                ->first();
        } else {
            $neighbor = (clone $base)
                ->where('position', '>', $statement->position)
                ->orderBy('position')
                ->first();
        }

        if (!$neighbor) return;

        $temp = $statement->position;
        $statement->position = $neighbor->position;
        $neighbor->position = $temp;

        $statement->save();
        $neighbor->save();
    }
}
