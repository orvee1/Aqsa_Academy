<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageAlbumRequest;
use App\Models\ImageAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageAlbumController extends Controller
{
    public function index(Request $request)
    {
        $q = ImageAlbum::query()
            ->withCount('items')
            ->when($request->filled('q'), function ($qq) use ($request) {
                $k = "%{$request->q}%";
                $qq->where('title', 'like', $k);
            })
            ->when($request->filled('status'), function ($qq) use ($request) {
                if ($request->status === '1') {
                    $qq->where('status', 1);
                }

                if ($request->status === '0') {
                    $qq->where('status', 0);
                }

            })
            ->latest('id');

        $albums = $q->paginate(15)->appends(request()->query());

        return view('admin.image_albums.index', compact('albums'));
    }

    public function create()
    {
        return view('admin.image_albums.create');
    }

    public function store(ImageAlbumRequest $request)
    {
        ImageAlbum::create($request->validated());
        return redirect()->route('admin.image-albums.index')->with('success', 'Album created.');
    }

    public function edit(ImageAlbum $image_album)
    {
        return view('admin.image_albums.edit', ['album' => $image_album]);
    }

    public function update(ImageAlbumRequest $request, ImageAlbum $image_album)
    {
        $image_album->update($request->validated());
        return redirect()->route('admin.image-albums.index')->with('success', 'Album updated.');
    }

    public function destroy(ImageAlbum $image_album)
    {
        // items cascade delete হবে, কিন্তু ফাইল delete ImageItemController@destroy এ হয়
        // তাই album delete করার আগে items ফাইল delete করাই best (quick approach এখানে করি)
        foreach ($image_album->items as $it) {
            Storage::disk('public')->delete($it->image_path);
        }
        $image_album->delete();

        return back()->with('success', 'Album deleted.');
    }

    public function toggle(ImageAlbum $image_album)
    {
        $image_album->update(['status' => ! $image_album->status]);
        return back()->with('success', 'Album status updated.');
    }
}
