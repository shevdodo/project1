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
    <title>{{ $product->name }} - {{ $siteTitle }}</title>
    
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <x-theme-config />
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col" x-data="{ lightboxOpen: false }">
    <x-frontend-navbar />

    <main class="flex-grow pt-8 pb-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-8 sm:p-12 flex items-center justify-center bg-gray-50">
                    @if($product->image)
                        <div class="cursor-pointer group relative w-full max-w-md" @click="lightboxOpen = true">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded-2xl shadow-lg object-cover transition transform group-hover:scale-[1.02] duration-300">
                            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center">
                                <div class="bg-white/90 p-3 rounded-full text-gray-800 shadow-xl transform scale-90 group-hover:scale-100 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="w-full max-w-md aspect-square bg-gray-200 rounded-2xl flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endif
                </div>
                
                <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
                    <!-- Breadcrumbs (Map Slug) -->
                    <nav class="flex items-center text-xs sm:text-sm text-gray-500 uppercase tracking-widest font-semibold mb-4 space-x-2">
                        <a href="{{ url('/') }}" class="hover:text-brand-600 transition">Home</a>
                        <span class="text-gray-300">/</span>
                        <a href="{{ route('product.index') }}" class="hover:text-brand-600 transition">Store</a>
                        @if($product->category)
                        <span class="text-gray-300">/</span>
                        <a href="{{ route('product.category', $product->category->slug) }}" class="text-gray-900 hover:text-brand-600 transition">{{ $product->category->name }}</a>
                        @endif
                    </nav>
                    
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">{{ $product->name }}</h1>
                    
                    {{-- Price, Weight, and Stock details --}}
                    <div class="mb-6 flex flex-col gap-2">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-brand-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex items-center gap-3 flex-wrap mt-2">
                            @if($product->weight)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                                    {{ $product->weight }} gr
                                </span>
                            @endif

                            @if($product->stock > 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Ready Stock ({{ $product->stock }})
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Stok Habis
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="prose prose-sm text-gray-600 mb-8">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                    
                    <div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            
                            @php
                                $sizeList = [];
                                if (!empty($product->sizes)) {
                                    $sizeList = array_filter(array_map('trim', explode(',', $product->sizes)));
                                }
                            @endphp

                            @if(!empty($sizeList))
                                <div class="mb-6" x-data="{ selectedSize: '{{ reset($sizeList) }}' }">
                                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Pilih Ukuran:</label>
                                    <div class="flex flex-wrap gap-2">
                                        <input type="hidden" name="size" :value="selectedSize">
                                        @foreach($sizeList as $size)
                                            <button type="button" @click="selectedSize = '{{ $size }}'"
                                                :class="selectedSize === '{{ $size }}' ? 'bg-brand-600 text-white border-brand-600 shadow-sm' : 'bg-white text-gray-800 border-gray-200 hover:border-gray-300'"
                                                class="px-4 py-2 text-sm font-semibold border rounded-xl transition duration-150">
                                                {{ $size }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <button type="submit" 
                                @if($product->stock <= 0) disabled @endif
                                class="w-full sm:w-auto px-8 py-4 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 bg-brand-600 hover:bg-brand-700 shadow-brand-600/30 disabled:bg-gray-300 disabled:text-gray-400 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none">
                                @if($product->stock <= 0)
                                    Stok Habis
                                @else
                                    Add to Cart
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-700 mb-8 uppercase tracking-wider border-b border-gray-200 pb-3">Related Products</h2>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-8">
                @foreach($relatedProducts as $relProduct)
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-lg transition duration-300">
                    <a href="{{ route('product.show', ['category_slug' => $relProduct->category ? $relProduct->category->slug : 'uncategorized', 'slug' => $relProduct->slug]) }}" class="relative block w-full aspect-square bg-gray-100 overflow-hidden">
                        @if($relProduct->image)
                        <img src="{{ asset('storage/' . $relProduct->image) }}" alt="{{ $relProduct->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
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
                        <div class="mb-1">
                            <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider line-clamp-1">{{ $relProduct->category ? $relProduct->category->name : 'Uncategorized' }}</span>
                        </div>
                        <h3 class="text-sm sm:text-lg font-bold text-gray-900 mb-1 sm:mb-2 leading-tight line-clamp-2">
                            <a href="{{ route('product.show', ['category_slug' => $relProduct->category ? $relProduct->category->slug : 'uncategorized', 'slug' => $relProduct->slug]) }}" class="hover:text-brand-600 transition">{{ $relProduct->name }}</a>
                        </h3>
                        <div class="mt-auto">
                            <span class="text-sm sm:text-lg font-extrabold text-brand-600">Rp {{ number_format($relProduct->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    <x-frontend-footer />

    <!-- Lightbox Modal -->
    @if($product->image)
    <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-sm" x-transition.opacity>
        <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white transition z-10 bg-black/50 p-2 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="max-w-[90vw] max-h-[90vh] object-contain shadow-2xl" @click.away="lightboxOpen = false">
        <div class="absolute bottom-6 left-0 right-0 text-center pointer-events-none">
            <p class="text-white/80 text-lg font-medium tracking-wider drop-shadow-md">{{ $product->name }}</p>
        </div>
    </div>
    @endif
</body>
</html>
