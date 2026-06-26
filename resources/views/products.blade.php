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
    <title>Store - {{ $siteTitle }}</title>
    
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
                        Our Products
                    @endif
                </h1>

                <!-- Search Form -->
                <form action="{{ isset($category) ? route('product.category', $category->slug) : route('product.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
                    <div class="relative flex-grow md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all bg-white text-sm">
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
                <a href="{{ route('product.index') }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors border {{ !isset($category) ? 'bg-brand-600 text-white border-brand-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-brand-300 hover:text-brand-600' }}">All Products</a>
                @foreach($categories as $cat)
                    <a href="{{ route('product.category', $cat->slug) }}" class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors border {{ isset($category) && $category->id == $cat->id ? 'bg-brand-600 text-white border-brand-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-brand-300 hover:text-brand-600' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
            @endif
            
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-8">
                @forelse($products as $product)
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-lg transition duration-300">
                    <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}" class="relative block w-full aspect-square bg-gray-100 overflow-hidden">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center hidden sm:flex">
                            <span class="px-6 py-2 bg-white text-gray-900 font-bold rounded-full shadow-lg transform -translate-y-4 group-hover:translate-y-0 transition duration-300">View Details</span>
                        </div>
                    </a>
                    
                    <div class="p-3 sm:p-5 flex-grow flex flex-col">
                        @if($product->category)
                        <div class="mb-1">
                            <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider line-clamp-1">{{ $product->category->name }}</span>
                        </div>
                        @endif
                        <h2 class="text-sm sm:text-lg font-bold text-gray-900 mb-1 sm:mb-2 leading-tight line-clamp-2">
                            <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}" class="hover:text-brand-600 transition">{{ $product->name }}</a>
                        </h2>
                        <div class="mt-auto pt-2 sm:pt-4">
                            <span class="font-extrabold text-brand-600 text-sm sm:text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <p class="text-lg">No products available at the moment.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        </div>
    </main>

    <x-frontend-footer />
</body>
</html>
