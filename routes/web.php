<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Super\TenantController;
use App\Http\Controllers\Super\UserController as SuperUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InstituteProfileController;

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth', 'verified', 'tenant'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/institute', [InstituteProfileController::class, 'edit'])->name('institute.edit');
        Route::put('/institute', [InstituteProfileController::class, 'update'])->name('institute.update');

        // --- module stubs (menu items) ---
        Route::view('/notices', 'admin.stub', ['title' => 'নোটিশবোর্ড'])->name('notices');
        Route::view('/members', 'admin.stub', ['title' => 'মেম্বার'])->name('members');
        Route::view('/pages', 'admin.stub', ['title' => 'পেজ তৈরি করুন'])->name('pages');
        Route::view('/posts', 'admin.stub', ['title' => 'পোস্ট তৈরি করুন'])->name('posts');
        Route::view('/banners', 'admin.stub', ['title' => 'ব্যানার তৈরি করুন'])->name('banners');
        Route::view('/events', 'admin.stub', ['title' => 'ইভেন্ট'])->name('events');
        Route::view('/achievements', 'admin.stub', ['title' => 'আমাদের অর্জন'])->name('achievements');
        Route::view('/image-gallery', 'admin.stub', ['title' => 'ইমেজ গ্যালারি'])->name('image_gallery');
        Route::view('/video-gallery', 'admin.stub', ['title' => 'ভিডিও গ্যালারি'])->name('video_gallery');
        Route::view('/sidebar', 'admin.stub', ['title' => 'সাইডবার ম্যানেজ'])->name('sidebar');
        Route::view('/footer', 'admin.stub', ['title' => 'ফুটার ম্যানেজ'])->name('footer');
    });
});

Route::middleware(['auth', 'verified', 'super'])->prefix('super')->name('super.')->group(function () {
    Route::resource('tenants', TenantController::class);
    Route::resource('users', SuperUserController::class)->only(['index','create','store','edit','update']);
});

require __DIR__.'/auth.php';
