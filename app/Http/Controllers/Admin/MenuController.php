<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $q = Menu::query()
            ->when($request->filled('q'), function($qq) use ($request){
                $k = "%{$request->q}%";
                $qq->where('name','like',$k)->orWhere('location','like',$k);
            })
            ->when($request->filled('location'), fn($qq)=> $qq->where('location', $request->location))
            ->when($request->filled('status'), function($qq) use ($request){
                if ($request->status === '1') $qq->where('status',1);
                if ($request->status === '0') $qq->where('status',0);
            })
            ->latest('id');

        $menus = $q->paginate(15)->appends(request()->query());

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(MenuRequest $request)
    {
        Menu::create($request->validated());
        return redirect()->route('admin.menus.index')->with('success','Menu created.');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->update($request->validated());
        return redirect()->route('admin.menus.index')->with('success','Menu updated.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success','Menu deleted.');
    }
}
