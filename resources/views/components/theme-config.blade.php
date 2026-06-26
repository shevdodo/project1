@php
    $themeColor = \App\Models\Setting::where('key', 'theme_color')->value('value') ?: 'brown';
    $themeFont = \App\Models\Setting::where('key', 'theme_font')->value('value') ?: 'figtree';

    // Define palettes
    $palettes = [
        'indigo' => "{ 50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81', 950: '#1e1b4b' }",
        'brown' => "{ 50: '#fbf8f5', 100: '#f4ede6', 200: '#ebd8c9', 300: '#dfbc9f', 400: '#d29b71', 500: '#c87f4c', 600: '#ba663e', 700: '#9b4e33', 800: '#7d412e', 900: '#653628', 950: '#361912' }",
        'emerald' => "{ 50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b', 950: '#022c22' }",
        'rose' => "{ 50: '#fff1f2', 100: '#ffe4e6', 200: '#fecdd3', 300: '#fda4af', 400: '#fb7185', 500: '#f43f5e', 600: '#e11d48', 700: '#be123c', 800: '#9f1239', 900: '#881337', 950: '#4c0519' }",
    ];

    $activePalette = $palettes[$themeColor] ?? $palettes['brown'];

    // Define fonts
    $fonts = [
        'figtree' => ['url' => 'https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap', 'name' => 'Figtree', 'css' => "'Figtree', sans-serif"],
        'inter' => ['url' => 'https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap', 'name' => 'Inter', 'css' => "'Inter', sans-serif"],
        'merriweather' => ['url' => 'https://fonts.bunny.net/css?family=merriweather:300,400,700,900&display=swap', 'name' => 'Merriweather', 'css' => "'Merriweather', serif"],
        'poppins' => ['url' => 'https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800&display=swap', 'name' => 'Poppins', 'css' => "'Poppins', sans-serif"],
    ];

    $activeFont = $fonts[$themeFont] ?? $fonts['figtree'];
@endphp

<!-- Dynamic Font Link -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="{{ $activeFont['url'] }}" rel="stylesheet" />

<!-- Dynamic Tailwind Configuration -->
<script>
    if (typeof tailwind !== 'undefined') {
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['{!! $activeFont["name"] !!}', 'sans-serif'],
                        serif: ['{!! $activeFont["name"] !!}', 'serif']
                    },
                    colors: {
                        brand: {!! $activePalette !!}
                    },
                }
            }
        }
    }
</script>

<!-- Global Style Overrides -->
<style>
    body {
        font-family: {!! $activeFont['css'] !!};
    }
</style>
