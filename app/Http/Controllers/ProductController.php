<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();
        
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }
        
        $products = $query->paginate(10)->appends($request->all());
        $categories = Category::where('type', 'product')->get();
        
        return view('dashboard.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'weight'      => 'nullable|integer|min:0',
            'stock'       => 'nullable|integer|min:0',
            'sizes'       => 'nullable|string|max:255',
            'status'      => 'required|in:available,unavailable',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        if (empty($data['price'])) {
            $data['price'] = 0;
        }

        if (!isset($data['stock']) || $data['stock'] === '') {
            $data['stock'] = 0;
        }

        if ($request->hasFile('image')) {
            $folder = 'media/' . date('Y/m');
            $file = $request->file('image');
            $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $file->storeAs($folder, $safeName, 'public');
        } elseif ($request->has('image_media_path')) {
            $data['image'] = $request->input('image_media_path');
        }

        Product::create($data);

        return redirect()->route('superuser.products.index')->with('status', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'weight'      => 'nullable|integer|min:0',
            'stock'       => 'nullable|integer|min:0',
            'sizes'       => 'nullable|string|max:255',
            'status'      => 'required|in:available,unavailable',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (empty($data['price'])) {
            $data['price'] = 0;
        }

        if (!isset($data['stock']) || $data['stock'] === '') {
            $data['stock'] = 0;
        }

        if ($request->hasFile('image')) {
            $folder = 'media/' . date('Y/m');
            $file = $request->file('image');
            $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $file->storeAs($folder, $safeName, 'public');
        } elseif ($request->has('image_media_path')) {
            $data['image'] = $request->input('image_media_path');
        }

        $product->update($data);

        return redirect()->route('superuser.products.index')->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('superuser.products.index')->with('status', 'Product deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('superuser.products.index')->with('status', 'No products selected.');
        }
        $count = Product::whereIn('id', $ids)->count();
        Product::whereIn('id', $ids)->delete();
        return redirect()->route('superuser.products.index')->with('status', "{$count} product(s) deleted successfully.");
    }
}
