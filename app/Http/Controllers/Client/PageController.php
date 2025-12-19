<?php
namespace App\Http\Controllers\Client;

use App\Models\Page;

class PageController extends BaseClientController
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return $this->view('client.pages.show', compact('page'));
    }
}
