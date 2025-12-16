<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageItemRequest;
use App\Models\ImageAlbum;
use App\Models\ImageItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageItemController extends Controller
{
    public function index(ImageAlbum $album, Request $request)
    {
        $items = $album->items()
            ->when($request->filled('status'), function ($qq) use ($request) {
                if ($request->status === '1') {
                    $qq->where('status', 1);
                }

                if ($request->status === '0') {
                    $qq->where('status', 0);
                }

            })
            ->orderBy('position')
            ->orderByDesc('id')
            ->paginate(24)
            ->appends(request()->query());

        return view('admin.image_albums.items', compact('album', 'items'));
    }

    // Bulk upload OR single create
    public function store(ImageItemRequest $request)
    {
        $data    = $request->validated();
        $albumId = (int) $data['album_id'];

        // bulk upload: images[]
        if ($request->hasFile('images')) {
            $posBase = (int) ImageItem::where('album_id', $albumId)->max('position');
            $pos     = $posBase ?: 0;

            foreach ($request->file('images') as $file) {
                $pos++;

                ImageItem::create([
                    'album_id'   => $albumId,
                    'title'      => null,
                    'caption'    => null,
                    'image_path' => $file->store('gallery/images', 'public'),
                    'position'   => $pos,
                    'status'     => true,
                ]);
            }

            return back()->with('success', 'Images uploaded.');
        }

        // single create: image
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('gallery/images', 'public');
        } else {
            return back()->with('error', 'Please select image.');
        }

        ImageItem::create($data);
        return back()->with('success', 'Image added.');
    }

    public function edit(ImageItem $imageItem)
    {
        $album = $imageItem->album;
        return view('admin.image_items.edit', compact('imageItem', 'album'));
    }

    public function update(ImageItemRequest $request, ImageItem $imageItem)
    {
        $data = $request->validated();

        // album_id change allow করবেন না (safe)
        $data['album_id'] = $imageItem->album_id;

        if ($request->hasFile('image')) {
            if ($imageItem->image_path) {
                Storage::disk('public')->delete($imageItem->image_path);
            }

            $data['image_path'] = $request->file('image')->store('gallery/images', 'public');
        }

        $imageItem->update($data);

        return redirect()->route('admin.image-albums.items', $imageItem->album_id)->with('success', 'Image updated.');
    }

    public function destroy(ImageItem $imageItem)
    {
        $albumId = $imageItem->album_id;
        if ($imageItem->image_path) {
            Storage::disk('public')->delete($imageItem->image_path);
        }

        $imageItem->delete();

        return back()->with('success', 'Image deleted.');
    }

    public function toggle(ImageItem $imageItem)
    {
        $imageItem->update(['status' => ! $imageItem->status]);
        return back()->with('success', 'Image status updated.');
    }

    public function up(ImageItem $imageItem)
    {
        $this->swap($imageItem, 'up');
        return back();
    }

    public function down(ImageItem $imageItem)
    {
        $this->swap($imageItem, 'down');
        return back();
    }

    private function swap(ImageItem $item, string $dir): void
    {
        $q = ImageItem::query()->where('album_id', $item->album_id);

        if ($dir === 'up') {
            $neighbor = (clone $q)->where('position', '<', $item->position)->orderByDesc('position')->first();
        } else {
            $neighbor = (clone $q)->where('position', '>', $item->position)->orderBy('position')->first();
        }

        if (! $neighbor) {
            return;
        }

        $tmp                = $item->position;
        $item->position     = $neighbor->position;
        $neighbor->position = $tmp;

        $item->save();
        $neighbor->save();
    }
}
