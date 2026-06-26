<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
    $favIcon = \App\Models\Setting::where('key', 'fav_icon')->value('value');
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blog - {{ $siteTitle }}</title>
    
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <x-theme-config />
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col">
    <x-frontend-navbar />

    <main class="flex-grow pt-8 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <h1 class="text-3xl font-extrabold text-gray-900">
                    @if(isset($category))
                        {{ $category->name }}
                    @else
                        Latest Posts
                    @endif
                </h1>

                <!-- Search Form -->
                <form action="{{ isset($category) ? route('post.category', $category->slug) : route('post.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
                    <div class="relative flex-grow md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all bg-white text-sm">
                        <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-colors text-sm shadow-sm whitespace-nowrap">
                        Search
                    </button>
                </form>
            </div>

            <!-- Categories Pill Filter -->
            @if(isset($categories) && $categories->count() > 0)
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('post.index') }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors border {{ !isset($category) ? 'bg-brand-600 text-white border-brand-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-brand-300 hover:text-brand-600' }}">All Posts</a>
                @foreach($categories as $cat)
                    <a href="{{ route('post.category', $cat->slug) }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors border {{ isset($category) && $category->id == $cat->id ? 'bg-brand-600 text-white border-brand-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-brand-300 hover:text-brand-600' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($posts as $post)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition">
                    @if($post->image)
                    <a href="{{ route('post.show', ['category_slug' => $post->category ? $post->category->slug : 'uncategorized', 'slug' => $post->slug]) }}" class="block w-full h-48 overflow-hidden">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform hover:scale-105 transition duration-300">
                    </a>
                    @else
                    <a href="{{ route('post.show', ['category_slug' => $post->category ? $post->category->slug : 'uncategorized', 'slug' => $post->slug]) }}" class="block w-full h-48 bg-gray-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                    </a>
                    @endif
                    <div class="p-6 flex-grow flex flex-col">
                        @if($post->category)
                        <div class="mb-2">
                            <span class="text-xs font-semibold text-brand-600 uppercase tracking-wider">{{ $post->category->name }}</span>
                        </div>
                        @endif
                        <h2 class="text-xl font-bold text-gray-900 mb-2">
                            <a href="{{ route('post.show', ['category_slug' => $post->category ? $post->category->slug : 'uncategorized', 'slug' => $post->slug]) }}" class="hover:text-brand-600 transition">{{ $post->title }}</a>
                        </h2>
                        <p class="text-gray-500 text-sm mb-4 flex-grow">{{ Str::limit($post->excerpt ?? $post->content, 100) }}</p>
                        <div class="text-xs text-gray-400 mt-auto">
                            {{ $post->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-2xl border border-gray-100">
                    No posts available right now.
                </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        </div>
    </main>

    <x-frontend-footer />
</body>
</html>
