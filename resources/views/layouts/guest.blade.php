<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
            $tagline = \App\Models\Setting::where('key', 'tagline')->value('value') ?: 'Welcome back! Please enter your details.';
            $favIcon = \App\Models\Setting::where('key', 'fav_icon')->value('value');
            $siteIcon = \App\Models\Setting::where('key', 'site_icon')->value('value');
            $siteIconShape = \App\Models\Setting::where('key', 'site_icon_shape')->value('value') ?? 'square';
            $shapeClass = 'rounded-xl';
            if ($siteIconShape == 'circle') $shapeClass = 'rounded-full';
            elseif ($siteIconShape == 'rectangle') $shapeClass = 'rounded-xl';
        @endphp

        <title>{{ $siteTitle }} - Login</title>

        @if($favIcon)
            <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
        @endif

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <x-theme-config />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-brand-50 relative overflow-hidden">
        
        <!-- Decorative Background -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-brand-300/30 blur-[120px]"></div>
            <div class="absolute top-[60%] -right-[10%] w-[60%] h-[60%] rounded-full bg-brand-400/20 blur-[150px]"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <div class="mb-8 text-center flex flex-col items-center justify-center space-y-4 z-10">
                <a href="{{ url('/') }}" class="group flex flex-col items-center space-y-3">
                    @if($siteIcon)
                        <img src="{{ asset('storage/' . $siteIcon) }}" alt="{{ $siteTitle }}" class="h-16 {{ $shapeClass }} {{ $siteIconShape != 'rectangle' ? 'w-16 object-cover' : 'w-auto object-contain max-w-[200px]' }} shadow-xl shadow-brand-500/20 transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-xl shadow-brand-500/30 transition-transform duration-300 group-hover:scale-105">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                    @endif
                    <span class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $siteTitle }}</span>
                </a>
                <p class="text-sm text-gray-500 max-w-sm">{{ $tagline }}</p>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white/80 backdrop-blur-xl shadow-2xl shadow-brand-900/10 overflow-hidden sm:rounded-3xl border border-white/50 relative z-10">
                <div class="absolute inset-0 bg-gradient-to-b from-white/60 to-transparent pointer-events-none"></div>
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-8 text-center text-xs text-gray-400 z-10">
                &copy; {{ date('Y') }} {{ $siteTitle }}. All rights reserved.
            </div>
        </div>
    </body>
</html>
