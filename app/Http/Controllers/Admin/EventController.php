<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $q = Event::query()
            ->with('creator:id,name')
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('title', 'like', $k)
                    ->orWhere('slug', 'like', $k)
                    ->orWhere('venue', 'like', $k);
            })
            ->when($request->filled('status'), fn($qq) => $qq->where('status', $request->status))
            ->when($request->filled('date_from'), fn($qq) => $qq->whereDate('event_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($qq) => $qq->whereDate('event_date', '<=', $request->date_to))
            ->orderByDesc('event_date')
            ->orderByDesc('id');

        $events = $q->paginate(15)->appends(request()->query());

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(EventRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('events/covers', 'public');
        }

        $data['created_by'] = auth()->id();

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(EventRequest $request, Event $event)
    {
        $data = $request->validated();

        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $event->id);

        if ($request->hasFile('cover_image')) {
            if ($event->cover_image_path) {
                Storage::disk('public')->delete($event->cover_image_path);
            }

            $data['cover_image_path'] = $request->file('cover_image')->store('events/covers', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        if ($event->cover_image_path) {
            Storage::disk('public')->delete($event->cover_image_path);
        }

        $event->delete();

        return back()->with('success', 'Event deleted.');
    }

    public function toggleStatus(Event $event)
    {
        $event->status = $event->status === 'published' ? 'draft' : 'published';
        $event->save();

        return back()->with('success', 'Event status updated.');
    }

    private function uniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = Str::slug($text) ?: 'event';
        $slug = $base;
        $i    = 1;

        while (
            Event::query()
            ->when($ignoreId, fn($qq) => $qq->where('id', '!=', $ignoreId))
            ->where('slug', $slug)->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
