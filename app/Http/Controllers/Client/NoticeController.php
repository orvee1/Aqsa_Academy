<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::where('is_published',true)
            ->where('is_hidden',false)
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(15);

        return view('client.notices.index', compact('notices'));
    }

    public function show(string $slug)
    {
        $notice = Notice::where('slug',$slug)
            ->where('is_published',true)
            ->where('is_hidden',false)
            ->firstOrFail();

        return view('client.notices.show', compact('notice'));
    }
}
