<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends BaseClientController
{
    public function index()
    {
        $notices = Notice::where('is_published', true)
            ->where('is_hidden', false)
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(15)
            ->appends(request()->query());

        return $this->view('client.notices.index', compact('notices'));
    }

    public function show(string $slug)
    {
        $notice = Notice::where('slug', $slug)
            ->where('is_published', true)
            ->where('is_hidden', false)
            ->firstOrFail();

        return $this->view('client.notices.show', compact('notice'));
    }
}
