<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ImageAlbum;
use App\Models\ImageItem;
use Illuminate\Http\Request;

class GalleryController extends BaseClientController
{
    public function index()
    {
        $albums = ImageAlbum::where('status', true)->latest('id')->paginate(12);

        // cover image + count
        $albumIds = $albums->getCollection()->pluck('id');
        $counts   = ImageItem::whereIn('album_id', $albumIds)
            ->selectRaw('album_id, COUNT(*) as total')
            ->groupBy('album_id')
            ->pluck('total', 'album_id');

        $covers = ImageItem::whereIn('album_id', $albumIds)
            ->where('status', true)
            ->orderBy('position')
            ->get()
            ->groupBy('album_id')
            ->map(fn($g) => $g->first()?->image_path);

        return $this->view('client.gallery.index', compact('albums', 'counts', 'covers'));
    }

    public function album(ImageAlbum $album)
    {
        abort_unless($album->status, 404);

        $items = ImageItem::where('album_id', $album->id)
            ->where('status', true)
            ->orderBy('position')
            ->paginate(24)
            ->appends(request()->query());

        return $this->view('client.gallery.album', compact('album', 'items'));
    }
}
