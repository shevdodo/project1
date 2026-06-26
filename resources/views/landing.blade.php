<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
    $tagline = \App\Models\Setting::where('key', 'tagline')->value('value') ?: 'A modern, professional platform built with Laravel. Secure, fast, and scalable – everything you need to manage your projects with confidence.';
    $favIcon = \App\Models\Setting::where('key', 'fav_icon')->value('value');

    // Load homepage content from database
    $homePage = \App\Models\Page::where('slug', '__homepage__')->first();
    $homeContent = [];
    if ($homePage && !empty($homePage->content)) {
        $decoded = json_decode($homePage->content, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $homeContent = $decoded;
        }
    }
    $hc = function($key, $default = '') use ($homeContent) {
        return $homeContent[$key] ?? $default;
    };
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $tagline }}">
    <title>{{ $siteTitle }} - {{ $tagline }}</title>
    
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Dynamic Theme Config -->
    <x-theme-config />
</head>

<body class="font-sans antialiased text-gray-900 bg-white">
    <!-- Navigation -->
    <x-frontend-navbar />

    @php
        $featuredProducts = \App\Models\Product::where('status', 'available')->latest()->take(8)->get();
        
        // Load categories: use selected IDs from CMS if available, otherwise show all (max 4)
        $selectedCategoryIds = isset($homeContent['featured_category_ids']) && !empty($homeContent['featured_category_ids'])
            ? array_filter((array) $homeContent['featured_category_ids'])
            : [];

        if (!empty($selectedCategoryIds)) {
            $productCategories = \App\Models\Category::where('type', 'product')
                ->whereIn('id', $selectedCategoryIds)
                ->orderByRaw('FIELD(id, ' . implode(',', array_map('intval', $selectedCategoryIds)) . ')')
                ->get();
        } else {
            $productCategories = \App\Models\Category::where('type', 'product')->take(4)->get();
        }
    @endphp

    <!-- Hero Section -->
    <section class="relative pb-32 lg:pb-40 overflow-hidden flex items-center min-h-[80vh]">
        <div class="absolute inset-0">
            <!-- Background Image -->
            @if(!empty($hc('hero_bg')))
                <img src="{{ asset('storage/' . $hc('hero_bg')) }}" alt="Batik Background" class="w-full h-full object-cover" />
            @else
                <img src="{{ asset('storage/slider_bg.jpg') }}" alt="Batik Background" class="w-full h-full object-cover" />
            @endif
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gray-900/40 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mt-20 sm:mt-0">
            <span class="inline-block px-4 py-1 rounded-full bg-white/20 text-white backdrop-blur-md border border-white/30 text-sm font-semibold tracking-wider uppercase mb-6 shadow-lg">{{ $hc('hero_badge', 'Koleksi Eksklusif') }}</span>
            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold text-white leading-tight mb-6 tracking-tight drop-shadow-md">
                {!! nl2br(e($hc('hero_title', "Keanggunan Tradisi\ndalam Balutan Modern"))) !!}
            </h1>
            <p class="text-lg sm:text-xl text-gray-200 max-w-2xl mx-auto mb-10 drop-shadow">
                {{ $hc('hero_subtitle', 'Temukan koleksi batik terbaik yang dirancang khusus untuk menyempurnakan gaya Anda di setiap momen.') }}
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('product.index') }}" class="px-8 py-4 bg-brand-600 text-white rounded-full text-lg font-bold hover:bg-brand-700 hover:scale-105 transition duration-300 shadow-[0_0_20px_rgba(79,70,229,0.4)]">
                    {{ $hc('hero_cta_primary', 'Belanja Sekarang') }}
                </a>
                <a href="{{ route('post.index') }}" class="px-8 py-4 bg-white/10 text-white border border-white/30 rounded-full text-lg font-bold hover:bg-white hover:text-gray-900 backdrop-blur-sm transition duration-300 shadow-lg">
                    {{ $hc('hero_cta_secondary', 'Lihat Jurnal') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-white -mt-10 relative z-20 rounded-t-3xl shadow-xl max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">{{ $hc('categories_title', 'Kategori Pilihan') }}</h2>
            <div class="w-16 h-1 bg-brand-600 mx-auto mt-4 rounded-full"></div>
        </div>
        @php
            $catCount = $productCategories->count();
            $gridClass = match(true) {
                $catCount === 1 => 'grid-cols-1 max-w-xs mx-auto',
                $catCount === 2 => 'grid-cols-2 max-w-md mx-auto',
                $catCount === 3 => 'grid-cols-3 max-w-2xl mx-auto',
                default         => 'grid-cols-2 md:grid-cols-4',
            };
        @endphp
        <div class="grid {{ $gridClass }} gap-6 sm:gap-8 justify-items-center">
            @foreach($productCategories as $cat)
                <a href="{{ route('product.category', $cat->slug) }}" class="group block text-center">
                    <div class="w-32 h-32 sm:w-40 sm:h-40 mx-auto rounded-full overflow-hidden mb-4 shadow-lg border-4 border-white group-hover:border-brand-100 transition duration-300 relative">
                        @if($cat->image)
                            <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" loading="lazy" width="300" height="300" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <img src="https://picsum.photos/300/300?random={{ $cat->id }}" alt="{{ $cat->name }}" loading="lazy" width="300" height="300" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @endif
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-300"></div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-brand-600 transition">{{ $cat->name }}</h3>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Latest Collection Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $hc('products_title', 'Koleksi Terbaru') }}</h2>
                    <p class="text-gray-500 mt-2">{{ $hc('products_subtitle', 'Pilihan busana batik terbaik untuk gaya Anda.') }}</p>
                </div>
                <a href="{{ route('product.index') }}" class="hidden sm:inline-flex items-center text-brand-600 font-semibold hover:text-brand-800 transition">
                    Lihat Semua
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-8">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl transition duration-300 flex flex-col">
                        <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}" class="relative block aspect-[3/4] overflow-hidden bg-gray-100">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" width="400" height="600" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8 sm:w-16 sm:h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                            
                            <!-- Quick actions overlay -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col items-center justify-center space-y-3 hidden sm:flex">
                                <span class="w-3/4 py-2 bg-white text-gray-900 text-center font-bold rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition duration-300">
                                    Lihat Detail
                                </span>
                            </div>
                        </a>
                        
                        <div class="p-3 sm:p-5 flex-grow flex flex-col">
                            @if($product->category)
                            <div class="text-[9px] sm:text-xs font-bold text-brand-700 uppercase tracking-wider mb-1 sm:mb-2 line-clamp-1">
                                {{ $product->category->name }}
                            </div>
                            @endif
                            <h3 class="text-sm sm:text-lg font-bold text-gray-900 mb-1 leading-tight line-clamp-2">
                                <a href="{{ route('product.show', ['category_slug' => $product->category ? $product->category->slug : 'uncategorized', 'slug' => $product->slug]) }}" class="hover:text-brand-600 transition">{{ $product->name }}</a>
                            </h3>
                            <div class="mt-auto pt-2 sm:pt-4">
                                <span class="font-extrabold text-gray-900 text-sm sm:text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-10 text-center sm:hidden">
                <a href="{{ route('product.index') }}" class="inline-flex items-center text-brand-600 font-semibold border border-brand-600 px-6 py-2 rounded-full hover:bg-brand-50 transition">
                    Lihat Semua Produk
                </a>
            </div>
        </div>
    </section>

    <!-- Value Proposition -->
    <section class="py-16 bg-brand-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
                <div>
                    <div class="w-16 h-16 mx-auto bg-white/10 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ $hc('vp_title_1', 'Kualitas Premium') }}</h3>
                    <p class="text-gray-400">{{ $hc('vp_desc_1', 'Dibuat dari bahan pilihan dengan teknik membatik terbaik yang diwariskan turun-temurun.') }}</p>
                </div>
                <div>
                    <div class="w-16 h-16 mx-auto bg-white/10 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ $hc('vp_title_2', 'Harga Kompetitif') }}</h3>
                    <p class="text-gray-400">{{ $hc('vp_desc_2', 'Dapatkan koleksi batik eksklusif dengan harga yang sesuai dengan kualitas yang diberikan.') }}</p>
                </div>
                <div>
                    <div class="w-16 h-16 mx-auto bg-white/10 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ $hc('vp_title_3', 'Layanan Cepat') }}</h3>
                    <p class="text-gray-400">{{ $hc('vp_desc_3', 'Tim kami siap membantu Anda dengan layanan responsif untuk setiap pertanyaan dan pemesanan.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <x-frontend-footer />
</body>

</html>