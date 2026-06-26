<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::all();
        $currentMenu = null;
        
        if ($request->has('menu')) {
            $currentMenu = Menu::with('parentItems.children')->find($request->menu);
        } elseif ($menus->count() > 0) {
            $currentMenu = Menu::with('parentItems.children')->first();
        }

        $pages = Page::where('status', 'published')->get();
        $categories = Category::all();

        return view('dashboard.menus.index', compact('menus', 'currentMenu', 'pages', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $menu = Menu::create($request->only('name'));
        return redirect()->route('superuser.menus.index', ['menu' => $menu->id])->with('status', 'Menu created successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('superuser.menus.index')->with('status', 'Menu deleted successfully.');
    }

    public function addItem(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:custom,page,category',
            'url' => 'nullable|string',
            'reference_id' => 'nullable|integer'
        ]);

        $order = $menu->items()->max('order') + 1;
        
        $menu->items()->create([
            'title' => $request->title,
            'type' => $request->type,
            'url' => $request->url,
            'reference_id' => $request->reference_id,
            'order' => $order
        ]);

        return redirect()->route('superuser.menus.index', ['menu' => $menu->id])->with('status', 'Item added to menu.');
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string'
        ]);

        $item->update([
            'title' => $request->title,
            'url' => $item->type == 'custom' ? $request->url : $item->url
        ]);

        return redirect()->route('superuser.menus.index', ['menu' => $item->menu_id])->with('status', 'Item updated successfully.');
    }

    public function removeItem(MenuItem $item)
    {
        $menuId = $item->menu_id;
        $item->delete();
        return redirect()->route('superuser.menus.index', ['menu' => $menuId])->with('status', 'Item removed.');
    }

    public function saveStructure(Request $request, Menu $menu)
    {
        $structure = json_decode($request->structure, true);
        
        if (is_array($structure)) {
            $this->updateItemHierarchy($structure, null);
        }

        return redirect()->route('superuser.menus.index', ['menu' => $menu->id])->with('status', 'Menu structure saved.');
    }

    private function updateItemHierarchy($items, $parentId)
    {
        foreach ($items as $index => $itemData) {
            $item = MenuItem::find($itemData['id']);
            if ($item) {
                $item->update([
                    'order' => $index,
                    'parent_id' => $parentId
                ]);

                if (!empty($itemData['children'])) {
                    $this->updateItemHierarchy($itemData['children'], $item->id);
                }
            }
        }
    }
}
