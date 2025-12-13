<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = app()->bound('tenant') ? app('tenant') : null;

        return view('admin.dashboard', [
            'tenant' => $tenant,
            'institute' => $tenant?->institute,
            'user' => $request->user(),
        ]);
    }
}
