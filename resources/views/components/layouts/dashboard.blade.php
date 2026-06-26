<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
        $favIcon = \App\Models\Setting::where('key', 'fav_icon')->value('value');
    @endphp
    <title>{{ $siteTitle }} - {{ $title ?? 'Dashboard' }}</title>

    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <x-theme-config />

    <!-- AlpineJS CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite (fallback if Laravel dev/build assets are available) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Figtree', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #4338ca; border-radius: 10px; }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 25px -8px rgba(0,0,0,0.15); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

<div class="min-h-screen flex" x-data="{ sidebarOpen: window.innerWidth >= 768, mobileMenu: false }">
    
    <!-- ====== SIDEBAR ====== -->
    <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-brand-950 text-white flex flex-col transition-all duration-300"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0 md:w-20'"
           x-show="sidebarOpen || window.innerWidth >= 768"
           x-cloak>
        
        <!-- Brand -->
        <div class="h-16 flex items-center justify-between px-5 border-b border-brand-800/60">
            <a href="{{ url('/') }}" class="flex items-center space-x-2.5">
                @php
                    $siteIcon = \App\Models\Setting::where('key', 'site_icon')->value('value');
                    $siteIconShape = \App\Models\Setting::where('key', 'site_icon_shape')->value('value') ?? 'square';
                    $shapeClass = 'rounded-md';
                    if ($siteIconShape == 'circle') $shapeClass = 'rounded-full';
                    elseif ($siteIconShape == 'rectangle') $shapeClass = 'rounded-md';
                @endphp
                @if($siteIcon)
                    <img src="{{ asset('storage/' . $siteIcon) }}" alt="{{ $siteTitle }}" class="h-8 {{ $shapeClass }} {{ $siteIconShape != 'rectangle' ? 'w-8 object-cover' : 'w-auto object-contain max-w-[120px]' }} bg-white/10 p-0.5">
                @else
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                @endif
                <span class="font-bold text-lg tracking-tight" x-show="sidebarOpen">
                    {{ $siteTitle }}
                </span>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-brand-200 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Sidebar Links -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-1">
            @if(Auth::check() && Auth::user()->role === 'superuser')
                <!-- Superuser Links -->
                <a href="{{ route('superuser.dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.dashboard') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <span class="{{ request()->routeIs('superuser.dashboard') ? 'font-medium' : '' }}">Overview</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('profile.edit') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200 group">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="{{ request()->routeIs('profile.edit') ? 'font-medium' : '' }}">My Profile</span>
                    @if(!request()->routeIs('profile.edit'))
                    <span class="ml-auto px-2 py-0.5 text-[10px] uppercase tracking-wider bg-brand-800/40 text-brand-300 rounded-md opacity-0 group-hover:opacity-100 transition">Edit</span>
                    @endif
                </a>

                <a href="{{ route('cart.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('cart.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="{{ request()->routeIs('cart.*') ? 'font-medium' : '' }}">My Cart</span>
                </a>

                <a href="{{ route('orders.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('orders.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <span class="{{ request()->routeIs('orders.*') ? 'font-medium' : '' }}">My Orders</span>
                </a>

                <div class="pt-3 mt-3 border-t border-brand-800/50">
                    <p class="px-4 text-[10px] uppercase tracking-widest text-brand-400 font-semibold mb-2">Frontend</p>
                    <a href="{{ route('superuser.pages.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.pages.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.pages.*') ? 'font-medium' : '' }}">Pages</span>
                    </a>
                    <a href="{{ route('superuser.posts.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.posts.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.posts.*') ? 'font-medium' : '' }}">Posts</span>
                    </a>
                    <a href="{{ route('superuser.products.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.products.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.products.*') ? 'font-medium' : '' }}">Products</span>
                    </a>
                    <a href="{{ route('superuser.categories.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.categories.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.categories.*') ? 'font-medium' : '' }}">Categories</span>
                    </a>
                    <a href="{{ route('superuser.menus.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.menus.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.menus.*') ? 'font-medium' : '' }}">Menus</span>
                    </a>
                    <a href="{{ route('superuser.settings.footer') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.settings.footer') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.settings.footer') ? 'font-medium' : '' }}">Footer</span>
                    </a>
                    
                    <a href="{{ route('superuser.settings.permalink') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.settings.permalink') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.settings.permalink') ? 'font-medium' : '' }}">Permalinks</span>
                    </a>
                    <a href="{{ route('superuser.media.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.media.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.media.*') ? 'font-medium' : '' }}">Media</span>
                    </a>
                </div>

                <div class="pt-3 mt-3 border-t border-brand-800/50">
                    <p class="px-4 text-[10px] uppercase tracking-widest text-brand-400 font-semibold mb-2">Administration</p>
                    <a href="{{ route('superuser.settings.general') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.settings.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.settings.general') ? 'font-medium' : '' }}">General Settings</span>
                    </a>
                    
                    <a href="{{ route('superuser.settings.theme') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.settings.theme') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.settings.theme') ? 'font-medium' : '' }}">Theme Settings</span>
                    </a>
                    <a href="{{ route('superuser.settings.api') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.settings.api') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.settings.api') ? 'font-medium' : '' }}">API Settings</span>
                    </a>
                    <a href="{{ route('superuser.users.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('superuser.users.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="{{ request()->routeIs('superuser.users.*') ? 'font-medium' : '' }}">User Management</span>
                    </a>
                    <a href="#"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-brand-200 hover:text-white hover:bg-brand-800/60 transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span>Audit Logs</span>
                    </a>
                </div>
            @else
                <!-- Standard User Links -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span class="{{ request()->routeIs('dashboard') ? 'font-medium' : '' }}">Dashboard</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('profile.edit') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200 group">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="{{ request()->routeIs('profile.edit') ? 'font-medium' : '' }}">My Profile</span>
                    @if(!request()->routeIs('profile.edit'))
                    <span class="ml-auto px-2 py-0.5 text-[10px] uppercase tracking-wider bg-brand-800/40 text-brand-300 rounded-md opacity-0 group-hover:opacity-100 transition">Edit</span>
                    @endif
                </a>

                <a href="{{ route('cart.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('cart.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="{{ request()->routeIs('cart.*') ? 'font-medium' : '' }}">My Cart</span>
                </a>

                <a href="{{ route('orders.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl {{ request()->routeIs('orders.*') ? 'text-white bg-gradient-to-r from-brand-600 to-brand-700 shadow-lg shadow-brand-900/30 font-medium' : 'text-brand-200 hover:text-white hover:bg-brand-800/60' }} transition-all duration-200">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <span class="{{ request()->routeIs('orders.*') ? 'font-medium' : '' }}">My Orders</span>
                </a>
            @endif

            <!-- Common Logout Link -->
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar-central').submit();"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-brand-200 hover:text-white hover:bg-red-700/40 transition-all duration-200 mt-auto">
                <div class="w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <span>Log Out</span>
            </a>
            <form id="logout-form-sidebar-central" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
        </nav>

        <!-- Sidebar Footer / User Info -->
        <div class="p-3 mx-3 mb-3 rounded-xl bg-brand-900/60 border border-brand-800/40 backdrop-blur-sm flex items-center justify-between">
            <div class="flex items-center space-x-3" x-show="sidebarOpen">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center font-bold text-white uppercase text-sm shadow-lg shadow-brand-900/40">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-brand-300 truncate capitalize font-medium">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" x-show="sidebarOpen">
                @csrf
                <button type="submit" class="p-2 text-brand-300 hover:text-white rounded-lg hover:bg-brand-800/60 transition" title="Log Out">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- ====== MAIN CONTENT AREA ====== -->
    <div class="flex-1 flex flex-col min-h-screen"
         :class="sidebarOpen ? 'md:ml-64' : 'md:ml-20'">
        
        <!-- ====== TOPBAR ====== -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20 shadow-sm">
            <!-- Left: Hamburger + Title -->
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-brand-600 p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-lg sm:text-xl font-bold text-gray-800 truncate">{{ $title ?? 'Dashboard' }}</h1>
            </div>

            <!-- Right: Search + Notif + Profile dropdown -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Search (hidden on mobile) -->
                <div class="hidden sm:relative sm:flex sm:items-center">
                    <svg class="absolute left-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="Search..." class="w-48 lg:w-64 pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 transition">
                </div>

                <!-- Notification -->
                <button class="relative p-2 text-gray-500 hover:text-brand-600 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center shadow">3</span>
                </button>

                <!-- Mobile search toggle / responsive -->
                <button class="sm:hidden p-2 text-gray-500 hover:text-brand-600 rounded-lg hover:bg-gray-100 transition"
                        @click="mobileMenu = !mobileMenu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 p-1.5 rounded-lg hover:bg-gray-100 transition">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center font-bold text-white uppercase text-xs shadow">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700 hidden sm:inline-block">{{ Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-52 bg-white border border-gray-200 rounded-xl shadow-xl py-2 z-30">
                        <div class="px-4 py-2 border-b border-gray-100 mb-1">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>My Profile</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- ====== MOBILE SEARCH BAR (shown when toggled) ====== -->
        <div x-show="mobileMenu" x-cloak
             class="sm:hidden bg-white border-b border-gray-200 px-4 py-3 shadow-sm">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Search anything..." class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 transition">
            </div>
        </div>

        <!-- ====== MAIN PANEL CONTENT ====== -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
            {{ $slot }}
        </main>

        <!-- ====== FOOTER ====== -->
        <footer class="border-t border-gray-200 bg-white px-6 py-3 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} {{ $siteTitle }}. All rights reserved. <span class="hidden sm:inline">Built with Laravel</span>
        </footer>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen && window.innerWidth < 768"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/40 z-20 md:hidden" x-cloak>
    </div>
</div>

@stack('scripts')
</body>
</html>