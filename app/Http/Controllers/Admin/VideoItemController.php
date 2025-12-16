<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoItemRequest;
use App\Models\VideoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoItemController extends Controller
{
    public function index(Request $request)
    {
        $q = VideoItem::query()
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('title', 'like', $k)->orWhere('youtube_url', 'like', $k);
            })
            ->when($request->filled('status'), function ($qq) use ($request) {
                if ($request->status === '1') {
                    $qq->where('status', 1);
                }

                if ($request->status === '0') {
                    $qq->where('status', 0);
                }

            })
            ->orderBy('position')
            ->orderByDesc('id');

        $videos = $q->paginate(15)->appends(request()->query());

        return view('admin.video_items.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.video_items.create');
    }

    public function store(VideoItemRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('videos/thumbnails', 'public');
        }

        VideoItem::create($data);

        return redirect()->route('admin.video-items.index')->with('success', 'Video created.');
    }

    public function edit(VideoItem $video_item)
    {
        return view('admin.video_items.edit', ['video' => $video_item]);
    }

    public function update(VideoItemRequest $request, VideoItem $video_item)
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            if ($video_item->thumbnail_path) {
                Storage::disk('public')->delete($video_item->thumbnail_path);
            }

            $data['thumbnail_path'] = $request->file('thumbnail')->store('videos/thumbnails', 'public');
        }

        $video_item->update($data);

        return redirect()->route('admin.video-items.index')->with('success', 'Video updated.');
    }

    public function destroy(VideoItem $video_item)
    {
        if ($video_item->thumbnail_path) {
            Storage::disk('public')->delete($video_item->thumbnail_path);
        }

        $video_item->delete();

        return back()->with('success', 'Video deleted.');
    }

    public function toggle(VideoItem $video_item)
    {
        $video_item->update(['status' => ! $video_item->status]);
        return back()->with('success', 'Status updated.');
    }

    public function up(VideoItem $video_item)
    {
        $this->swap($video_item, 'up');
        return back();
    }

    public function down(VideoItem $video_item)
    {
        $this->swap($video_item, 'down');
        return back();
    }

    private function swap(VideoItem $v, string $dir): void
    {
        $q = VideoItem::query();

        if ($dir === 'up') {
            $neighbor = (clone $q)->where('position', '<', $v->position)->orderByDesc('position')->first();
        } else {
            $neighbor = (clone $q)->where('position', '>', $v->position)->orderBy('position')->first();
        }

        if (! $neighbor) {
            return;
        }

        $tmp                = $v->position;
        $v->position        = $neighbor->position;
        $neighbor->position = $tmp;

        $v->save();
        $neighbor->save();
    }
}
