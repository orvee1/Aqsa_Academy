<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoticeRequest;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $q = Notice::query()
            ->when($request->filled('q'), function($qq) use ($request){
                $k = "%{$request->q}%";
                $qq->where('title','like',$k)->orWhere('slug','like',$k);
            })
            ->when($request->filled('hidden'), function($qq) use ($request){
                if ($request->hidden === '1') $qq->where('is_hidden', 1);
                if ($request->hidden === '0') $qq->where('is_hidden', 0);
            })
            ->when($request->filled('published'), function($qq) use ($request){
                if ($request->published === '1') $qq->where('is_published', 1);
                if ($request->published === '0') $qq->where('is_published', 0);
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('id');

        $notices = $q->paginate(15)->appends(request()->query());

        return view('admin.notices.index', compact('notices'));
    }

    public function store(NoticeRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = $this->makeUniqueSlug($request->input('slug') ?: $request->title);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('notices', 'public');
        }

        $data['created_by'] = auth()->id();

        Notice::create($data);

        return back()->with('success', 'Notice created.');
    }

    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', compact('notice'));
    }

    public function update(NoticeRequest $request, Notice $notice)
    {
        $data = $request->validated();

        // slug update (optional)
        if (!empty($data['slug'])) {
            $data['slug'] = $this->makeUniqueSlug($data['slug'], $notice->id);
        } else {
            $data['slug'] = $this->makeUniqueSlug($notice->title, $notice->id);
        }

        if ($request->hasFile('file')) {
            if ($notice->file_path) Storage::disk('public')->delete($notice->file_path);
            $data['file_path'] = $request->file('file')->store('notices', 'public');
        }

        $notice->update($data);

        return redirect()->route('admin.notices.index')->with('success', 'Notice updated.');
    }

    public function destroy(Notice $notice)
    {
        if ($notice->file_path) Storage::disk('public')->delete($notice->file_path);
        $notice->delete();

        return back()->with('success', 'Notice deleted.');
    }

    // --- Screenshot অনুযায়ী Hide/Unhide eye ---
    public function toggleHide(Notice $notice)
    {
        $notice->update(['is_hidden' => !$notice->is_hidden]);
        return back()->with('success', 'Hide/Unhide updated.');
    }

    public function togglePublish(Notice $notice)
    {
        $notice->update(['is_published' => !$notice->is_published]);
        return back()->with('success', 'Publish status updated.');
    }

    public function togglePin(Notice $notice)
    {
        $notice->update(['is_pinned' => !$notice->is_pinned]);
        return back()->with('success', 'Pin updated.');
    }

    private function makeUniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = Str::slug($text);
        $slug = $base ?: 'notice';

        $i = 1;
        while (
            Notice::query()
                ->when($ignoreId, fn($qq)=> $qq->where('id','!=',$ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
