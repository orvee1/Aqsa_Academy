<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Event;
use App\Models\FooterLink;
use App\Models\FooterSetting;
use App\Models\ImageAlbum;
use App\Models\Institute;
use App\Models\Menu;
use App\Models\Notice;
use App\Models\Slider;
use App\Models\SocialLink;
use App\Models\Statement;
use App\Models\VideoItem;
use Illuminate\Support\Collection;

class HomeController extends BaseClientController
{

    public function index()
    {
        $institute = Institute::latest('id')->first();

        $headerMenu = Menu::where('location', 'header')->where('status', true)->first();
        $menuItems  = $headerMenu
            ? $headerMenu->items()->where('status', true)->orderBy('position')->get()
            : collect();
        $menuTree = $this->buildTree($menuItems);

        $sliders = Slider::where('status', true)
            ->where(function ($q) {$q->whereNull('start_at')->orWhere('start_at', '<=', now());})
            ->where(function ($q) {$q->whereNull('end_at')->orWhere('end_at', '>=', now());})
            ->orderBy('position')->take(8)->get();

        $notices = Notice::where('is_published', true)
            ->where('is_hidden', false)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->latest('id')
            ->take(6)
            ->get();

        $statement = Statement::where('status', true)->orderBy('position')->first();

        $events = Event::where('status', 'published')
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->take(2)
            ->get();

        $achievements = Achievement::where('status', true)->orderBy('position')->take(3)->get();

        $albums = ImageAlbum::where('status', true)->latest('id')->take(3)->get();

        $videos = VideoItem::where('status', true)->orderBy('position')->take(3)->get();

        $footer      = FooterSetting::latest('id')->first();
        $footerLinks = FooterLink::where('status', true)->orderBy('group_title')->orderBy('position')->get()->groupBy('group_title');
        $socialLinks = SocialLink::where('status', true)->orderBy('position')->get();

        return view('client.home', compact(
            'institute', 'menuTree', 'sliders', 'notices', 'statement', 'events',
            'achievements', 'albums', 'videos', 'footer', 'footerLinks', 'socialLinks'
        ));
    }
}
