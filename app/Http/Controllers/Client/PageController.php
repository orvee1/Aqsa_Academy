<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug',$slug)
            ->where('status','published')
            ->firstOrFail();

        return view('client.pages.show', compact('page'));
    }
}
