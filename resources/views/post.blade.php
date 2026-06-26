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
    <title>{{ $post->title }} - {{ $siteTitle }}</title>
    
    @if($favIcon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favIcon) }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <x-theme-config />
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 min-h-screen flex flex-col">
    <x-frontend-navbar />

    <main class="flex-grow pt-24 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover">
                @endif
                <div class="px-8 py-10 sm:px-12 sm:py-16">
                    @if($post->category)
                    <div class="mb-4">
                        <span class="inline-block px-3 py-1 bg-brand-50 text-brand-700 rounded-full text-xs font-semibold tracking-wide uppercase">
                            {{ $post->category->name }}
                        </span>
                    </div>
                    @endif
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight mb-4">{{ $post->title }}</h1>
                    <div class="flex items-center text-sm text-gray-500 mb-8 space-x-4">
                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="prose prose-lg prose-indigo max-w-none text-gray-600 leading-relaxed">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-frontend-footer />
</body>
</html>
