<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemRequest;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\PostCategory;

class MenuItemController extends Controller
{
    public function builder(Menu $menu)
    {
        // Parent select এর জন্য সব আইটেম (নিজেই parent না হয় সেটা update এ দেখব)
        $allItems = $menu->items()->orderBy('parent_id')->orderBy('position')->get();

        // Tree list দেখানোর জন্য
        $tree = $this->buildTree($allItems);

        $pages = Page::query()
            ->where('status', 'published')
            ->orderBy('title')
            ->get(['id', 'title']);

        $categories = PostCategory::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.menus.builder', compact('menu', 'allItems', 'tree', 'pages', 'categories'));
    }

    public function store(MenuItemRequest $request)
    {
        MenuItem::create($this->normalizeByType($request->validated()));
        return back()->with('success', 'Menu item added.');
    }

    public function edit(MenuItem $menuItem)
    {
        $menu = $menuItem->menu;

        $allItems = $menu->items()
            ->where('id', '!=', $menuItem->id)
            ->orderBy('parent_id')
            ->orderBy('position')
            ->get();

        $pages = Page::where('status', 'published')
            ->orderBy('title')
            ->get(['id', 'title']);

        $categories = PostCategory::where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.menu_items.edit', compact('menuItem', 'menu', 'allItems', 'pages', 'categories'));
    }

    public function update(MenuItemRequest $request, MenuItem $menuItem)
    {
        $data = $this->normalizeByType($request->validated());

        // same menu enforce
        $data['menu_id'] = $menuItem->menu_id;

        // parent cannot be itself
        if (! empty($data['parent_id']) && (int) $data['parent_id'] === (int) $menuItem->id) {
            $data['parent_id'] = null;
        }

        $menuItem->update($data);
        return redirect()->route('admin.menus.builder', $menuItem->menu_id)->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuId = $menuItem->menu_id;
        $menuItem->delete();
        return back()->with('success', 'Menu item deleted.');
    }

    public function toggle(MenuItem $menuItem)
    {
        $menuItem->update(['status' => ! $menuItem->status]);
        return back()->with('success', 'Menu item status updated.');
    }

    public function moveUp(MenuItem $menuItem)
    {
        $this->swapPosition($menuItem, 'up');
        return back();
    }

    public function moveDown(MenuItem $menuItem)
    {
        $this->swapPosition($menuItem, 'down');
        return back();
    }

    // ---------------- helpers ----------------

    private function normalizeByType(array $data): array
    {
        // type অনুযায়ী non-related fields null করে রাখি (clean DB)
        $type = $data['type'] ?? 'url';

        if ($type !== 'url') {
            $data['url'] = null;
        }

        if ($type !== 'page') {
            $data['page_id'] = null;
        }

        if ($type !== 'post_category') {
            $data['post_category_id'] = null;
        }

        if ($type !== 'route') {
            $data['route_name'] = null;
        }

        return $data;
    }

    private function buildTree($items)
    {
        $byParent = [];
        foreach ($items as $it) {
            $byParent[$it->parent_id ?? 0][] = $it;
        }

        $walk = function ($parentId) use (&$walk, &$byParent) {
            $nodes = $byParent[$parentId] ?? [];
            foreach ($nodes as $n) {
                $n->children_nodes = $walk($n->id);
            }
            return $nodes;
        };

        return $walk(0);
    }

    private function swapPosition(MenuItem $item, string $dir): void
    {
        $query = MenuItem::query()
            ->where('menu_id', $item->menu_id)
            ->where('parent_id', $item->parent_id);

        if ($dir === 'up') {
            $neighbor = (clone $query)->where('position', '<', $item->position)->orderByDesc('position')->first();
        } else {
            $neighbor = (clone $query)->where('position', '>', $item->position)->orderBy('position')->first();
        }

        if (! $neighbor) {
            return;
        }

        $temp               = $item->position;
        $item->position     = $neighbor->position;
        $neighbor->position = $temp;

        $item->save();
        $neighbor->save();
    }
}
