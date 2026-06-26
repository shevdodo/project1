@php
    $siteTitle = \App\Models\Setting::where('key', 'site_title')->value('value') ?: config('app.name', 'Laravel');
    $footerCol1Title = \App\Models\Setting::where('key', 'footer_column1_title')->value('value') ?: 'PRODUCT CATEGORIES';
    $footerAddress = \App\Models\Setting::where('key', 'footer_address')->value('value') ?: "Batik Mukti Solo\n\nAlamat: Jl. Sumantri, Dusun II, Kartasura, Kec. Kartasura, Kabupaten Sukoharjo, Jawa Tengah 57169";
    $footerWhatsapp = \App\Models\Setting::where('key', 'footer_whatsapp')->value('value') ?: '081329515082';
    $footerMapEmbed = \App\Models\Setting::where('key', 'footer_map_embed')->value('value');
    $footerCopyright = \App\Models\Setting::where('key', 'footer_copyright')->value('value') ?: '&copy; ' . date('Y') . ' ' . $siteTitle . '. All rights reserved.';
    
    // Default map embed if none
    if (!$footerMapEmbed) {
        $footerMapEmbed = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.0863805727195!2d110.73977507567784!3d-7.565551992448375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a14f4eb076ce5%3A0x889d1b0d0c3ebc9c!2sBatik%20Mukti%20Solo!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
    }
    
    $productCategories = \App\Models\Category::where('type', 'product')->take(5)->get();
@endphp

<footer class="bg-brand-900 text-brand-100 mt-auto pt-16 pb-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <!-- Column 1: Categories -->
            <div>
                <h4 class="text-white font-bold mb-6 text-sm tracking-widest uppercase border-b border-brand-700 pb-2 inline-block">{{ $footerCol1Title }}</h4>
                <ul class="space-y-3 border-t border-brand-800 pt-2">
                    @foreach($productCategories as $cat)
                        <li class="border-b border-brand-800/50 pb-2"><a href="{{ route('product.category', $cat->slug) }}" class="text-brand-200 hover:text-white transition flex items-center justify-between group">
                            <span>{{ $cat->name }}</span>
                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a></li>
                    @endforeach
                    <li class="border-b border-brand-800/50 pb-2"><a href="{{ route('product.index') }}" class="text-brand-200 hover:text-white transition flex items-center justify-between group">
                        <span>Store</span>
                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a></li>
                </ul>
            </div>
            
            <!-- Column 2: Customer Service -->
            <div>
                <h4 class="text-white font-bold mb-6 text-sm tracking-widest uppercase border-b border-brand-700 pb-2 inline-block">CUSTOMER SERVICE</h4>
                <div class="prose prose-sm prose-invert mb-6 text-brand-100">
                    {!! nl2br(e($footerAddress)) !!}
                </div>
                <div class="flex items-center space-x-2 text-brand-200 text-sm">
                    <span>Whatsapp : {{ $footerWhatsapp }}</span>
                </div>
            </div>
            
            <!-- Column 3: Lokasi -->
            <div>
                <h4 class="text-white font-bold mb-6 text-sm tracking-widest uppercase border-b border-brand-700 pb-2 inline-block">LOKASI</h4>
                <div class="overflow-hidden bg-white/5 p-1 rounded">
                    {!! str_replace('<iframe ', '<iframe title="Google Maps Location" class="w-full h-48 md:h-64" ', $footerMapEmbed) !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-brand-950 py-6 mt-8 border-t border-brand-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
            <div class="flex space-x-4 sm:space-x-6 text-xs sm:text-sm mb-4 md:mb-0">
                <a href="{{ url('/') }}" class="text-brand-300 hover:text-white uppercase font-medium transition">BERANDA</a>
                <a href="{{ route('product.index') }}" class="text-brand-300 hover:text-white uppercase font-medium transition">STORE</a>
                <a href="{{ url('/tentang-kami') }}" class="text-brand-300 hover:text-white uppercase font-medium transition">TENTANG KAMI</a>
                <a href="{{ url('/kontak') }}" class="text-brand-300 hover:text-white uppercase font-medium transition">KONTAK KAMI</a>
            </div>
            <div class="text-brand-400 text-sm">
                {!! $footerCopyright !!}
            </div>
            <div class="text-brand-500 hover:text-brand-300 transition cursor-pointer hidden md:block">
                <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
            </div>
        </div>
    </div>
</footer>
