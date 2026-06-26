<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('category')->latest();
        
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }
        
        $posts = $query->paginate(10)->appends($request->all());
        $categories = Category::where('type', 'post')->get();
        
        return view('dashboard.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('superuser.posts.index')->with('status', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('dashboard.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image']);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('superuser.posts.index')->with('status', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('superuser.posts.index')->with('status', 'Post deleted successfully.');
    }
}
