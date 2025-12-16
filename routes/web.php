<?php
use App\Http\Controllers\Admin\InstituteController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

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

    Route::patch('notices/{notice}/toggle-hide', [NoticeController::class,'toggleHide'])->name('notices.toggle-hide');
    Route::patch('notices/{notice}/toggle-publish', [NoticeController::class,'togglePublish'])->name('notices.toggle-publish');
    Route::patch('notices/{notice}/toggle-pin', [NoticeController::class,'togglePin'])->name('notices.toggle-pin');

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
    Route::patch('pages/{page}/toggle-status', [PageController::class,'toggleStatus'])->name('pages.toggle-status');

    // Post and Post Category Routes
    Route::resource('post-categories', PostCategoryController::class);
    Route::patch('post-categories/{post_category}/toggle', [PostCategoryController::class,'toggle'])->name('post-categories.toggle');

    Route::resource('posts', PostController::class)->except(['show']);
    Route::patch('posts/{post}/toggle-status', [PostController::class,'toggleStatus'])->name('posts.toggle-status');
});

require __DIR__.'/auth.php';
