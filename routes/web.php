<?php

use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FooterLinkController;
use App\Http\Controllers\Admin\FooterSettingController;
use App\Http\Controllers\Admin\ImageAlbumController;
use App\Http\Controllers\Admin\ImageItemController;
use App\Http\Controllers\Admin\InstituteController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\StatementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClientHomeController::class, 'index'])->name('client.home');

Route::get('/notice-board', [ClientNoticeController::class, 'index'])->name('client.notices.index');
Route::get('/notice/{slug}', [ClientNoticeController::class, 'show'])->name('client.notices.show');

Route::get('/page/{slug}', [ClientPageController::class, 'show'])->name('client.pages.show');

Route::get('/news', [ClientPostController::class, 'index'])->name('client.posts.index');
Route::get('/news/{slug}', [ClientPostController::class, 'show'])->name('client.posts.show');
Route::get('/category/{slug}', [ClientPostController::class, 'category'])->name('client.posts.category');

Route::get('/events', [ClientEventController::class, 'index'])->name('client.events.index');
Route::get('/events/{slug}', [ClientEventController::class, 'show'])->name('client.events.show');

Route::get('/achievements', [ClientAchievementController::class, 'index'])->name('client.achievements.index');

Route::get('/gallery', [ClientGalleryController::class, 'index'])->name('client.gallery.index');
Route::get('/gallery/album/{album}', [ClientGalleryController::class, 'album'])->name('client.gallery.album');

Route::get('/videos', [ClientVideoController::class, 'index'])->name('client.videos.index');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Route
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Roles Routes
    Route::resource('roles', RoleController::class);
    Route::patch('roles/{role}/toggle', [RoleController::class, 'toggle'])->name('roles.toggle');

    // User Routes
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-super', [UserController::class, 'toggleSuper'])->name('users.toggle-super');

    // Permission Routes
    Route::resource('permissions', PermissionController::class);
    Route::patch('permissions/{permission}/toggle', [PermissionController::class, 'toggle'])->name('permissions.toggle');
    // role wise permission assign
    Route::get('roles/{role}/permissions', [RolePermissionController::class, 'edit'])->name('roles.permissions.edit');
    Route::put('roles/{role}/permissions', [RolePermissionController::class, 'update'])->name('roles.permissions.update');

    // Institute Routes
    Route::resource('institutes', InstituteController::class);
    Route::patch('institutes/{institute}/toggle', [InstituteController::class, 'toggle'])->name('institutes.toggle');

    // Notice Routes
    Route::resource('notices', NoticeController::class)->except(['show']);

    Route::patch('notices/{notice}/toggle-hide', [NoticeController::class, 'toggleHide'])->name('notices.toggle-hide');
    Route::patch('notices/{notice}/toggle-publish', [NoticeController::class, 'togglePublish'])->name('notices.toggle-publish');
    Route::patch('notices/{notice}/toggle-pin', [NoticeController::class, 'togglePin'])->name('notices.toggle-pin');

    // Menu and Menu Item Routes
    Route::resource('menus', MenuController::class);

    Route::get('menus/{menu}/builder', [MenuItemController::class, 'builder'])->name('menus.builder');

    Route::post('menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
    Route::get('menu-items/{menuItem}/edit', [MenuItemController::class, 'edit'])->name('menu-items.edit');
    Route::put('menu-items/{menuItem}', [MenuItemController::class, 'update'])->name('menu-items.update');
    Route::delete('menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');

    Route::patch('menu-items/{menuItem}/toggle', [MenuItemController::class, 'toggle'])->name('menu-items.toggle');
    Route::patch('menu-items/{menuItem}/up', [MenuItemController::class, 'moveUp'])->name('menu-items.up');
    Route::patch('menu-items/{menuItem}/down', [MenuItemController::class, 'moveDown'])->name('menu-items.down');

    // Page Routes
    Route::resource('pages', PageController::class)->except(['show']);
    Route::patch('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');

    // Post and Post Category Routes
    Route::resource('post-categories', PostCategoryController::class);
    Route::patch('post-categories/{post_category}/toggle', [PostCategoryController::class, 'toggle'])->name('post-categories.toggle');

    Route::resource('posts', PostController::class)->except(['show']);
    Route::patch('posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggle-status');

    // Statement Routes
    Route::resource('statements', StatementController::class)->except(['show']);
    Route::patch('statements/{statement}/toggle', [StatementController::class, 'toggle'])->name('statements.toggle');
    Route::patch('statements/{statement}/up', [StatementController::class, 'up'])->name('statements.up');
    Route::patch('statements/{statement}/down', [StatementController::class, 'down'])->name('statements.down');

    // Event Routes
    Route::resource('events', EventController::class)->except(['show']);
    Route::patch('events/{event}/toggle-status', [EventController::class, 'toggleStatus'])->name('events.toggle-status');

    // Achievement Routes
    Route::resource('achievements', AchievementController::class)->except(['show']);
    Route::patch('achievements/{achievement}/toggle', [AchievementController::class, 'toggle'])->name('achievements.toggle');
    Route::patch('achievements/{achievement}/up', [AchievementController::class, 'up'])->name('achievements.up');
    Route::patch('achievements/{achievement}/down', [AchievementController::class, 'down'])->name('achievements.down');

    // Image Album and Album Items Routes
    Route::resource('image-albums', ImageAlbumController::class)->except(['show']);
    Route::patch('image-albums/{image_album}/toggle', [ImageAlbumController::class, 'toggle'])->name('image-albums.toggle');

    // album items manage
    Route::get('image-albums/{album}/items', [ImageItemController::class, 'index'])->name('image-albums.items');
    Route::post('image-items', [ImageItemController::class, 'store'])->name('image-items.store');

    Route::get('image-items/{imageItem}/edit', [ImageItemController::class, 'edit'])->name('image-items.edit');
    Route::put('image-items/{imageItem}', [ImageItemController::class, 'update'])->name('image-items.update');
    Route::delete('image-items/{imageItem}', [ImageItemController::class, 'destroy'])->name('image-items.destroy');

    Route::patch('image-items/{imageItem}/toggle', [ImageItemController::class, 'toggle'])->name('image-items.toggle');
    Route::patch('image-items/{imageItem}/up', [ImageItemController::class, 'up'])->name('image-items.up');
    Route::patch('image-items/{imageItem}/down', [ImageItemController::class, 'down'])->name('image-items.down');

    // Video Album Routes
    Route::resource('video-items', VideoItemController::class)->except(['show']);
    Route::patch('video-items/{video_item}/toggle', [VideoItemController::class, 'toggle'])->name('video-items.toggle');
    Route::patch('video-items/{video_item}/up', [VideoItemController::class, 'up'])->name('video-items.up');
    Route::patch('video-items/{video_item}/down', [VideoItemController::class, 'down'])->name('video-items.down');

    // Slider Routes
    Route::resource('sliders', SliderController::class)->except(['show']);
    Route::patch('sliders/{slider}/toggle', [SliderController::class, 'toggle'])->name('sliders.toggle');
    Route::patch('sliders/{slider}/up', [SliderController::class, 'up'])->name('sliders.up');
    Route::patch('sliders/{slider}/down', [SliderController::class, 'down'])->name('sliders.down');

    // Footer Settings (single page)
    Route::get('footer/settings', [FooterSettingController::class, 'edit'])->name('footer.settings');
    Route::put('footer/settings', [FooterSettingController::class, 'update'])->name('footer.settings.update');

    // Footer Links
    Route::resource('footer-links', FooterLinkController::class)->except(['show']);
    Route::patch('footer-links/{footer_link}/toggle', [FooterLinkController::class, 'toggle'])->name('footer-links.toggle');
    Route::patch('footer-links/{footer_link}/up', [FooterLinkController::class, 'up'])->name('footer-links.up');
    Route::patch('footer-links/{footer_link}/down', [FooterLinkController::class, 'down'])->name('footer-links.down');

    // Social Links
    Route::resource('social-links', SocialLinkController::class)->except(['show']);
    Route::patch('social-links/{social_link}/toggle', [SocialLinkController::class, 'toggle'])->name('social-links.toggle');
    Route::patch('social-links/{social_link}/up', [SocialLinkController::class, 'up'])->name('social-links.up');
    Route::patch('social-links/{social_link}/down', [SocialLinkController::class, 'down'])->name('social-links.down');

    // Media Routes
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::get('media/picker', [MediaController::class, 'picker'])->name('media.picker');
});

// login page: /admin
Route::middleware('guest')->group(function () {
    Route::get('/admin', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/admin', [AuthenticatedSessionController::class, 'store']);
});

// logout
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
require __DIR__ . '/auth.php';
