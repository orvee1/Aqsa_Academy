<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\VideoItem;
use Illuminate\Http\Request;

class VideoController extends BaseClientController
{
     public function index()
    {
        $videos = VideoItem::where('status', true)
            ->orderBy('position')
            ->latest('id')
            ->paginate(12);

        return $this->view('client.videos.index', compact('videos'));
    }
}
