<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Event;
use App\Models\FooterLink;
use App\Models\FooterSetting;
use App\Models\ImageAlbum;
use App\Models\Media;
use App\Models\Notice;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Role;
use App\Models\Slider;
use App\Models\SocialLink;
use App\Models\Statement;
use App\Models\User;
use App\Models\VideoItem;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Dashboard এ বারবার heavy query না চলার জন্য ছোট cache (30 sec)
        $data = Cache::remember('admin_dashboard_v1', 30, function () use ($today) {

            // Notices summary
            $noticesTotal     = Notice::count();
            $noticesPublished = Notice::where('is_published', true)->where('is_hidden', false)->count();
            $noticesPinned    = Notice::where('is_published', true)->where('is_pinned', true)->count();

            // Pages / Posts
            $pagesTotal     = Page::count();
            $pagesPublished = Page::where('status', 'published')->count();

            $postsTotal     = Post::count();
            $postsPublished = Post::where('status', 'published')->count();
            $postCategories = PostCategory::count();

            // Statements / Achievements
            $statementsActive   = Statement::where('status', true)->count();
            $achievementsActive = Achievement::where('status', true)->count();

            // Events
            $eventsTotal    = Event::count();
            $eventsUpcoming = Event::where('status', 'published')
                ->whereDate('event_date', '>=', $today)
                ->count();

            // Gallery
            $albums = ImageAlbum::count();
            $videos = VideoItem::count();

            // Sliders
            $slidersTotal     = Slider::count();
            $slidersActiveNow = Slider::where('status', true)
                ->where(function ($q) {
                    $q->whereNull('start_at')->orWhere('start_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_at')->orWhere('end_at', '>=', now());
                })
                ->count();

            // Footer
            $footerConfigured  = FooterSetting::query()->exists();
            $footerLinksActive = FooterLink::where('status', true)->count();
            $socialLinksActive = SocialLink::where('status', true)->count();

            // Access
            $usersTotal       = User::count();
            $superAdmins      = User::where('is_super_admin', true)->count();
            $rolesTotal       = Role::count();
            $permissionsTotal = Permission::count();

            // Media
            $mediaTotal = Media::count();
            $mediaSize  = (int) Media::whereNotNull('size')->sum('size');

            // Recent activity blocks
            $recentNotices = Notice::latest('id')->take(5)->get(['id', 'title', 'is_published', 'is_hidden', 'created_at']);
            $recentPosts   = Post::latest('id')->take(5)->get(['id', 'title', 'status', 'created_at']);
            $recentMedia   = Media::latest('id')->with('uploader:id,name')->take(6)->get(['id', 'disk', 'path', 'mime', 'size', 'uploaded_by', 'created_at']);

            $upcomingEventsList = Event::where('status', 'published')
                ->whereDate('event_date', '>=', $today)
                ->orderBy('event_date')
                ->orderBy('event_time')
                ->take(6)
                ->get(['id', 'title', 'event_date', 'event_time', 'venue']);

            return compact(
                'noticesTotal', 'noticesPublished', 'noticesPinned',
                'pagesTotal', 'pagesPublished',
                'postsTotal', 'postsPublished', 'postCategories',
                'statementsActive', 'achievementsActive',
                'eventsTotal', 'eventsUpcoming', 'upcomingEventsList',
                'albums', 'videos',
                'slidersTotal', 'slidersActiveNow',
                'footerConfigured', 'footerLinksActive', 'socialLinksActive',
                'usersTotal', 'superAdmins', 'rolesTotal', 'permissionsTotal',
                'mediaTotal', 'mediaSize',
                'recentNotices', 'recentPosts', 'recentMedia'
            );
        });

        return view('admin.dashboard.index', $data);
    }
}
