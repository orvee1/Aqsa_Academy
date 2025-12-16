<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaUploadRequest;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $q = Media::query()->with('uploader:id,name');

        if ($request->filled('q')) {
            $k = "%{$request->q}%";
            $q->where('path', 'like', $k)->orWhere('mime', 'like', $k);
        }

        if ($request->filled('disk')) {
            $q->where('disk', $request->disk);
        }

        if ($request->filled('type')) {
            if ($request->type === 'image') {
                $q->where('mime', 'like', 'image/%');
            }

            if ($request->type === 'pdf') {
                $q->where('mime', 'like', 'application/pdf%');
            }

        }

        if ($request->filled('uploaded_by')) {
            $q->where('uploaded_by', (int) $request->uploaded_by);
        }

        $media = $q->latest('id')->paginate(24)->appends(request()->query());

        $disks = ['public']; // আপনার migration default public, চাইলে config থেকে নেব
        return view('admin.media.index', compact('media', 'disks'));
    }

    public function store(MediaUploadRequest $request)
    {
        $disk = $request->input('disk', 'public');

        foreach ($request->file('files') as $file) {
            $path = $file->store('uploads', $disk);

            Media::create([
                'disk'        => $disk,
                'path'        => $path,
                'mime'        => $file->getClientMimeType(),
                'size'        => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Files uploaded.');
    }

    public function destroy(Media $media)
    {
        // file delete
        if ($media->path && Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        $media->delete();

        return back()->with('success', 'Media deleted.');
    }
}
