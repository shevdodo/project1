@php
    $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
    $siteIcon = \App\Models\Setting::where('key', 'site_icon')->value('value');
    $siteIconShape = \App\Models\Setting::where('key', 'site_icon_shape')->value('value') ?? 'square';
    $shapeClass = 'rounded-md';
    if ($siteIconShape == 'circle') $shapeClass = 'rounded-full';
    elseif ($siteIconShape == 'rectangle') $shapeClass = 'rounded-md';

    $menu = \App\Models\Menu::with('parentItems.children')->first();
@endphp

<nav class="fixed w-full z-50">
    <!-- Desktop Navigation -->
    <div class="hidden lg:block bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3">
                        @if($siteIcon)
                            <img src="{{ asset('storage/' . $siteIcon) }}" width="40" height="40" alt="{{ $siteTitle }}" class="h-10 {{ $shapeClass }} {{ $siteIconShape != 'rectangle' ? 'w-10 object-cover' : 'w-auto object-contain max-w-[140px]' }}">
                        @endif
                        <span class="text-2xl font-extrabold text-brand-700 tracking-tight">
                            {{ $siteTitle }}
                        </span>
                    </a>
                </div>
                
                <!-- Menu Items -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3 border-r border-gray-200 pr-4">
                        @if($menu)
                            @foreach($menu->parentItems as $item)
                                @if($item->children->count() > 0)
                                    <div class="relative group">
                                        <button class="bg-brand-50 text-brand-700 hover:bg-brand-100 hover:shadow-md px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 inline-flex items-center">
                                            <span>{{ $item->title }}</span>
                                            <svg class="ml-1.5 w-4 h-4 transition-transform duration-300 group-hover:-rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                        <div class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 shadow-xl rounded-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top scale-95 group-hover:scale-100 z-50 overflow-hidden">
                                            <div class="p-2 space-y-1">
                                                @foreach($item->children as $child)
                                                    <a href="{{ $child->resolved_url }}" class="block px-4 py-3 text-sm font-bold text-gray-600 hover:bg-brand-50 hover:text-brand-700 rounded-xl transition-colors">{{ $child->title }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ $item->resolved_url }}" class="bg-brand-50 text-brand-700 hover:bg-brand-100 hover:shadow-md px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">{{ $item->title }}</a>
                                @endif
                            @endforeach
                        @endif
                        <a href="{{ route('cart.index') }}" class="p-2 text-gray-600 hover:text-brand-600 relative transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @php $cartCount = count(session()->get('cart', [])); @endphp
                            @if($cartCount > 0)
                                <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-3">
                        @if (Route::has('login'))
                            @auth
                                @if (Auth::user()->isSuperuser())
                                    <a href="{{ route('superuser.dashboard') }}" class="bg-white border-2 border-brand-100 text-brand-700 hover:border-brand-200 hover:bg-brand-50 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">Dashboard</a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="bg-white border-2 border-brand-100 text-brand-700 hover:border-brand-200 hover:bg-brand-50 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">Dashboard</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-brand-600 px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-brand-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-brand-700 hover:shadow-lg hover:shadow-brand-600/30 transition-all duration-300 transform hover:-translate-y-0.5">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="lg:hidden flex flex-col w-full shadow-sm relative">
        <!-- Top bar: Brown background -->
        <div class="bg-brand-950 text-white text-center py-2 border-b border-brand-900">
            <span class="text-xs font-bold tracking-widest uppercase">{{ $siteTitle }}</span>
        </div>
        
        <!-- Middle bar: Hamburger - Logo - Cart -->
        <div class="bg-white flex justify-between items-center px-4 py-3 relative z-20 shadow-sm">
            <!-- Left: Hamburger -->
            <button type="button" aria-label="Toggle mobile menu" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="p-2 -ml-2 text-gray-500 focus:outline-none hover:bg-gray-50 rounded-lg">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            
            <!-- Center: Logo -->
            <a href="{{ url('/') }}" class="flex justify-center items-center">
                @if($siteIcon)
                    <img src="{{ asset('storage/' . $siteIcon) }}" width="48" height="48" alt="{{ $siteTitle }}" class="h-12 w-12 rounded-full object-cover border border-gray-100 shadow-sm">
                @else
                    <span class="text-xl font-bold text-brand-700">{{ $siteTitle }}</span>
                @endif
            </a>
            
            <!-- Right: Cart -->
            <a href="{{ route('cart.index') }}" class="p-2 -mr-2 text-gray-500 hover:text-brand-600 relative focus:outline-none rounded-lg hover:bg-gray-50">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                @if($cartCount > 0)
                    <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ $cartCount }}</span>
                @endif
            </a>
        </div>
        
        <!-- Bottom bar: Socials -->
        <div class="bg-gray-50 flex justify-center items-center py-2 space-x-6 border-b border-gray-200">
            <!-- Instagram Icon -->
            <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-brand-600 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
            <!-- Phone Icon -->
            <a href="#" aria-label="Phone" class="text-gray-500 hover:text-brand-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </a>
        </div>
        
        <!-- Mobile Menu Dropdown -->
        <div class="hidden bg-white shadow-2xl absolute w-full top-full z-10 border-b border-gray-100" id="mobile-menu">
            <div class="px-4 py-6 space-y-3 max-h-[70vh] overflow-y-auto">
                @if($menu)
                    @foreach($menu->parentItems as $item)
                        @if($item->children->count() > 0)
                            <div class="space-y-2">
                                <div class="px-4 py-3 bg-brand-50 text-brand-800 rounded-xl font-bold text-base">{{ $item->title }}</div>
                                <div class="pl-4 space-y-2">
                                    @foreach($item->children as $child)
                                        <a href="{{ $child->resolved_url }}" class="block px-4 py-3 bg-white border border-gray-100 text-gray-700 hover:text-brand-600 rounded-xl text-sm font-bold shadow-sm">{{ $child->title }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item->resolved_url }}" class="block text-center bg-brand-50 text-brand-700 hover:bg-brand-100 px-4 py-3.5 rounded-xl text-base font-bold transition shadow-sm">{{ $item->title }}</a>
                        @endif
                    @endforeach
                @endif
                
                <div class="border-t border-gray-100 pt-4 mt-4">
                    @if (Route::has('login'))
                        @auth
                            @if (Auth::user()->isSuperuser())
                                <a href="{{ route('superuser.dashboard') }}" class="block text-center bg-gray-50 text-gray-800 px-4 py-3.5 rounded-xl text-base font-bold transition border border-gray-200">Dashboard</a>
                            @else
                                <a href="{{ route('dashboard') }}" class="block text-center bg-gray-50 text-gray-800 px-4 py-3.5 rounded-xl text-base font-bold transition border border-gray-200">Dashboard</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block text-center text-brand-600 border-2 border-brand-100 hover:bg-brand-50 px-4 py-3.5 rounded-xl text-base font-bold transition">Log in</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Spacer to push content down below the fixed navbar -->
<div class="h-20 lg:h-20 hidden lg:block"></div>
<div class="h-[128px] lg:hidden block"></div>
