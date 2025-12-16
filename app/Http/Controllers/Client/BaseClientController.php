<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use App\Models\FooterSetting;
use App\Models\Institute;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PostCategory;
use App\Models\SocialLink;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class BaseClientController extends Controller
{
    protected function buildTree(Collection $items, $parentId = null): array
    {
        $branch = [];
        foreach ($items->where('parent_id', $parentId)->sortBy('position') as $item) {
            $branch[] = [
                'item'     => $item,
                'children' => $this->buildTree($items, $item->id),
            ];
        }
        return $branch;
    }

    protected function common(): array
    {
        $institute = Institute::latest('id')->first();

        // Header menu
        $headerMenu = Menu::where('location', 'header')->where('status', true)->first();
        $items      = $headerMenu
            ? $headerMenu->items()->where('status', true)->orderBy('position')->get()
            : collect();

        // Resolve URLs in controller (avoid DB queries in blade)
        $pageIds = $items->where('type', 'page')->pluck('page_id')->filter()->unique()->values();
        $catIds  = $items->where('type', 'post_category')->pluck('post_category_id')->filter()->unique()->values();

        $pages = Page::whereIn('id', $pageIds)->where('status', 'published')->get(['id', 'slug']);
        $cats  = PostCategory::whereIn('id', $catIds)->where('status', true)->get(['id', 'slug']);

        $pageMap = $pages->keyBy('id');
        $catMap  = $cats->keyBy('id');

        foreach ($items as $it) {
            $url = '#';

            if ($it->type === 'url' && $it->url) {
                $url = $it->url;
            }

            if ($it->type === 'page' && $it->page_id && $pageMap->has($it->page_id)) {
                $url = route('client.pages.show', $pageMap[$it->page_id]->slug);
            }

            if ($it->type === 'post_category' && $it->post_category_id && $catMap->has($it->post_category_id)) {
                $url = route('client.posts.category', $catMap[$it->post_category_id]->slug);
            }

            if ($it->type === 'route' && $it->route_name && Route::has($it->route_name)) {
                $url = route($it->route_name);
            }

            $it->resolved_url = $url;
        }

        $menuTree = $this->buildTree($items);

        // Footer
        $footer      = FooterSetting::latest('id')->first();
        $footerLinks = FooterLink::where('status', true)
            ->orderBy('group_title')
            ->orderBy('position')
            ->get()
            ->groupBy(fn($x) => $x->group_title ?: 'Links');

        $socialLinks = SocialLink::where('status', true)->orderBy('position')->get();

        return compact('institute', 'menuTree', 'footer', 'footerLinks', 'socialLinks');
    }

    protected function view(string $view, array $data = [])
    {
        return view($view, array_merge($this->common(), $data));
    }
}
