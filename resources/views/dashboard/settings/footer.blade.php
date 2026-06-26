<x-layouts.dashboard title="Footer Settings">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Footer Settings</h2>
            <p class="text-sm text-gray-500 mt-1">Manage the content of your website's footer columns.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('superuser.settings.footer.update') }}" method="POST" class="p-6 sm:p-8 space-y-8">
                @csrf
                
                <!-- Column 1: Categories -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Column 1: Categories</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Column Title</label>
                        <input type="text" name="footer_column1_title" value="{{ old('footer_column1_title', $settings['footer_column1_title'] ?? 'PRODUCT CATEGORIES') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors bg-gray-50/50">
                        <p class="text-xs text-gray-500 mt-1">Title for the product categories column. The categories will be displayed automatically.</p>
                    </div>
                </div>

                <!-- Column 2: Customer Service -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Column 2: Customer Service</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="footer_address" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors bg-gray-50/50">{{ old('footer_address', $settings['footer_address'] ?? "Batik Mukti Solo\n\nAlamat: Jl. Sumantri, Dusun II, Kartasura, Kec. Kartasura, Kabupaten Sukoharjo, Jawa Tengah 57169") }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                            <input type="text" name="footer_whatsapp" value="{{ old('footer_whatsapp', $settings['footer_whatsapp'] ?? '081329515082') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors bg-gray-50/50">
                        </div>
                    </div>
                </div>

                <!-- Column 3: Lokasi (Map Embed) -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Column 3: Location (Maps)</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Google Maps Embed Code (iframe)</label>
                        <textarea name="footer_map_embed" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors bg-gray-50/50 font-mono text-xs">{{ old('footer_map_embed', $settings['footer_map_embed'] ?? '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.0863805727195!2d110.73977507567784!3d-7.565551992448375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a14f4eb076ce5%3A0x889d1b0d0c3ebc9c!2sBatik%20Mukti%20Solo!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Paste the full &lt;iframe&gt; HTML code from Google Maps here.</p>
                    </div>
                </div>

                <!-- Bottom Section: Copyright -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Bottom Section: Copyright</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Copyright Text</label>
                        <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings['footer_copyright'] ?? '© ' . date('Y') . ' Batik Mukti Solo. All rights reserved.') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-brand-500 focus:ring-brand-500 transition-colors bg-gray-50/50">
                        <p class="text-xs text-gray-500 mt-1">This text appears at the very bottom of the page. You can use © or &amp;copy;.</p>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-brand-600 text-white px-6 py-3 rounded-xl font-medium shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition">
                        Save Footer Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
