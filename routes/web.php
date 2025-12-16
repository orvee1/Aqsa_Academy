<?php
use App\Http\Controllers\Admin\InstituteController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\PermissionController;
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
});

require __DIR__.'/auth.php';
