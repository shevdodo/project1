<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        // Load homepage first, then rest of pages
        $homePage = Page::where('slug', '__homepage__')->first();
        $pages = Page::where('slug', '!=', '__homepage__')->with(['category', 'parent'])->orderBy('order')->latest()->paginate(10);
        return view('dashboard.pages.index', compact('pages', 'homePage'));
    }

    public function create()
    {
        $categories = Category::where('type', 'page')->get();
        $parentPages = Page::whereNull('parent_id')->get();
        return view('dashboard.pages.create', compact('categories', 'parentPages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'category_id' => 'nullable|exists:categories,id',
            'parent_id' => 'nullable|exists:pages,id',
            'template' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        Page::create($data);

        return redirect()->route('superuser.pages.index')->with('status', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $categories = Category::where('type', 'page')->get();
        $parentPages = Page::where('id', '!=', $page->id)->whereNull('parent_id')->get();
        $productCategories = \App\Models\Category::where('type', 'product')->orderBy('name')->get();
        return view('dashboard.pages.edit', compact('page', 'categories', 'parentPages', 'productCategories'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'category_id' => 'nullable|exists:categories,id',
            'parent_id' => 'nullable|exists:pages,id',
            'template' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Handle homepage structured content
        if ($page->template === 'homepage' && $request->has('hp')) {
            $data['content'] = json_encode($request->input('hp'));
            $data['template'] = 'homepage';
            $data['status'] = 'published';
            $data['slug'] = '__homepage__';
        }

        $page->update($data);

        return redirect()->route('superuser.pages.index')->with('status', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('superuser.pages.index')->with('status', 'Page deleted successfully.');
    }
}
