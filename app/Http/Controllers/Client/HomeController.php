<?php
namespace App\Http\Controllers\Client;

use App\Models\Achievement;
use App\Models\Event;
use App\Models\ImageAlbum;
use App\Models\Notice;
use App\Models\Slider;
use App\Models\Statement;
use App\Models\VideoItem;

class HomeController extends BaseClientController
{
    public function index()
    {
        $sliders = Slider::where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', now());
            })
            ->orderBy('position')
            ->take(8)
            ->get();

        $notices = Notice::where('is_published', true)
            ->where('is_hidden', false)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->latest('id')
            ->take(8)
            ->get();

        $statement = Statement::where('status', true)
            ->orderBy('position')
            ->first();

        $events = Event::where('status', 'published')
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->take(6)
            ->get();

        $achievements = Achievement::where('status', true)
            ->orderBy('position')
            ->take(6)
            ->get();

        $albums = ImageAlbum::where('status', true)
            ->latest('id')
            ->take(6)
            ->get();

        $videos = VideoItem::where('status', true)
            ->orderBy('position')
            ->take(6)
            ->get();

        return $this->view('client.home', compact(
            'sliders',
            'notices',
            'statement',
            'events',
            'achievements',
            'albums',
            'videos'
        ));
    }
}
