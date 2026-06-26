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
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($page->content ?? $siteTitle), 150) }}">
    <title>{{ $page->title }} - {{ $siteTitle }}</title>
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <x-theme-config />
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col">
    <!-- Navigation -->
    <x-frontend-navbar />

    <!-- Page Content -->
    <main class="flex-grow {{ $page->template === 'blank' ? '' : 'pt-24 pb-12' }}">
        @if($page->template === 'blank')
            {!! $page->content !!}
        @else
        <div class="{{ $page->template === 'full-width' ? 'w-full' : 'max-w-4xl mx-auto px-4 sm:px-6 lg:px-8' }}">
            <div class="{{ $page->template === 'full-width' ? '' : 'bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden' }}">
                <div class="{{ $page->template === 'full-width' ? 'px-4 sm:px-8 py-10 sm:py-16' : 'px-8 py-10 sm:px-12 sm:py-16' }}">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight mb-8 {{ $page->template === 'full-width' ? 'text-center' : '' }}">{{ $page->title }}</h1>
                    
                    <div class="prose prose-lg prose-indigo {{ $page->template === 'full-width' ? 'max-w-7xl mx-auto' : 'max-w-none' }} text-gray-600 leading-relaxed">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </main>

    <x-frontend-footer />
</body>
</html>
