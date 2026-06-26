<x-layouts.dashboard title="Edit Page">
    <div class="mb-8">
        <a href="{{ route('superuser.pages.index') }}" class="text-sm text-brand-600 hover:text-brand-800 flex items-center space-x-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Back to Pages</span>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Page</h2>
        <p class="text-sm text-gray-500 mt-1">Update your content and visibility.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm font-medium">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('superuser.pages.update', $page) }}" class="flex flex-col lg:flex-row gap-8">
        @csrf
        @method('PUT')

        @if($page->template === 'homepage')
        {{-- ===== HOMEPAGE STRUCTURED EDITOR ===== --}}
        @php
            $hpContent = [];
            if (!empty($page->content)) {
                $decoded = json_decode($page->content, true);
                if (json_last_error() === JSON_ERROR_NONE) $hpContent = $decoded;
            }
        @endphp
        <input type="hidden" name="template" value="homepage">
        <input type="hidden" name="status" value="published">
        <input type="hidden" name="slug" value="{{ $page->slug }}">
        <input type="hidden" name="title" value="{{ $page->title }}">

        <div class="flex-1 space-y-6">
            {{-- Hero Section --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-brand-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Hero Section (Slider Utama)</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Badge Text <span class="text-xs text-gray-400 font-normal">(mis: "Koleksi Eksklusif")</span></label>
                        <input type="text" name="hp[hero_badge]" value="{{ $hpContent['hero_badge'] ?? 'Koleksi Eksklusif' }}" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Utama <span class="text-xs text-gray-400 font-normal">(gunakan Enter untuk baris baru)</span></label>
                        <textarea name="hp[hero_title]" rows="2" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring">{{ $hpContent['hero_title'] ?? "Keanggunan Tradisi\ndalam Balutan Modern" }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Subjudul / Deskripsi Hero</label>
                        <textarea name="hp[hero_subtitle]" rows="2" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring">{{ $hpContent['hero_subtitle'] ?? 'Temukan koleksi batik terbaik yang dirancang khusus untuk menyempurnakan gaya Anda di setiap momen.' }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tombol Utama (CTA)</label>
                            <input type="text" name="hp[hero_cta_primary]" value="{{ $hpContent['hero_cta_primary'] ?? 'Belanja Sekarang' }}" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tombol Kedua</label>
                            <input type="text" name="hp[hero_cta_secondary]" value="{{ $hpContent['hero_cta_secondary'] ?? 'Lihat Jurnal' }}" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sections Text --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Judul Seksi Produk & Kategori</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Seksi Kategori</label>
                        <input type="text" name="hp[categories_title]" value="{{ $hpContent['categories_title'] ?? 'Kategori Pilihan' }}" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Seksi Produk Terbaru</label>
                        <input type="text" name="hp[products_title]" value="{{ $hpContent['products_title'] ?? 'Koleksi Terbaru' }}" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Subjudul Seksi Produk</label>
                        <input type="text" name="hp[products_subtitle]" value="{{ $hpContent['products_subtitle'] ?? 'Pilihan busana batik terbaik untuk gaya Anda.' }}" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                    </div>
                </div>
            </div>

            {{-- Category Picker --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Kategori yang Ditampilkan</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Pilih kategori produk yang ingin ditampilkan di beranda. Jika tidak ada yang dipilih, sistem akan menampilkan 4 kategori pertama secara otomatis.</p>
                    </div>
                </div>
                @php
                    $savedCatIds = isset($hpContent['featured_category_ids']) ? (array) $hpContent['featured_category_ids'] : [];
                @endphp
                @if(isset($productCategories) && $productCategories->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($productCategories as $cat)
                    <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition
                        {{ in_array($cat->id, $savedCatIds) ? 'border-brand-400 bg-brand-50' : 'border-gray-200 hover:border-brand-300 hover:bg-gray-50' }}
                        category-label" data-id="{{ $cat->id }}">
                        <input type="checkbox"
                            name="hp[featured_category_ids][]"
                            value="{{ $cat->id }}"
                            {{ in_array($cat->id, $savedCatIds) ? 'checked' : '' }}
                            class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500 category-check">
                        @if($cat->image)
                            <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-100">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>
                            </div>
                        @endif
                        <span class="text-sm font-medium text-gray-800">{{ $cat->name }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-3">
                    <span id="cat-count">{{ count($savedCatIds) }}</span> kategori dipilih
                    @if(count($savedCatIds) > 4)
                        <span class="text-orange-500 font-medium">— disarankan maksimal 4</span>
                    @endif
                </p>
                @else
                <p class="text-sm text-gray-400 italic">Tidak ada kategori produk yang tersedia. Buat kategori terlebih dahulu di menu Categories.</p>
                @endif
            </div>

            {{-- Value Proposition --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Seksi Keunggulan (3 Kolom)</h3>
                </div>
                <div class="space-y-5">
                    @foreach([['1','Kualitas Premium','Dibuat dari bahan pilihan dengan teknik membatik terbaik yang diwariskan turun-temurun.'],
                              ['2','Harga Kompetitif','Dapatkan koleksi batik eksklusif dengan harga yang sesuai dengan kualitas yang diberikan.'],
                              ['3','Layanan Cepat','Tim kami siap membantu Anda dengan layanan responsif untuk setiap pertanyaan dan pemesanan.']] as [$n, $defTitle, $defDesc])
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Poin {{ $n }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Judul</label>
                                <input type="text" name="hp[vp_title_{{ $n }}]" value="{{ $hpContent['vp_title_'.$n] ?? $defTitle }}" class="w-full border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi</label>
                                <input type="text" name="hp[vp_desc_{{ $n }}]" value="{{ $hpContent['vp_desc_'.$n] ?? $defDesc }}" class="w-full border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring bg-white">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="w-full lg:w-72 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-1">Home</h3>
                <p class="text-xs text-gray-500 mb-4">Halaman beranda website utama Anda.</p>
                <div class="mb-4 p-3 bg-brand-50 rounded-xl border border-brand-100 text-xs text-brand-700">
                    <span class="font-semibold block mb-1">ℹ️ Tentang halaman ini</span>
                    Perubahan di sini akan langsung ditampilkan di halaman beranda tanpa mengubah tampilan atau desainnya.
                </div>
                <a href="{{ url('/') }}" target="_blank" class="block text-center text-xs text-brand-600 hover:underline mb-4">Lihat Halaman Beranda ↗</a>
                <button type="submit" class="w-full px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/30">
                    Simpan Perubahan
                </button>
                <a href="{{ route('superuser.pages.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700 mt-3">← Kembali</a>
            </div>
        </div>

        @else
        {{-- ===== REGULAR PAGE EDITOR ===== --}}

        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-1">Page Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                </div>

                <div class="mb-6">
                    <label for="slug" class="block text-sm font-semibold text-gray-800 mb-1">Slug</label>
                    <p class="text-xs text-gray-500 mb-2">Leave blank to auto-generate from title.</p>
                    <div class="flex items-center">
                        <span class="bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg px-3 py-2 text-gray-500 text-sm">{{ url('/') }}/</span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" class="flex-1 border-gray-300 rounded-r-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                    </div>
                </div>

                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-800 mb-1">Page Content</label>
                    <textarea id="content" name="content" rows="12" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">{{ old('content', $page->content) }}</textarea>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-80 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Page Attributes</h3>
                
                <div class="mb-4">
                    <label for="parent_id" class="block text-sm font-semibold text-gray-800 mb-1">Parent Page</label>
                    <select id="parent_id" name="parent_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="">(no parent)</option>
                        @foreach($parentPages as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $page->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="template" class="block text-sm font-semibold text-gray-800 mb-1">Template</label>
                    <select id="template" name="template" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="default" {{ old('template', $page->template) == 'default' ? 'selected' : '' }}>Default Template</option>
                        <option value="full-width" {{ old('template', $page->template) == 'full-width' ? 'selected' : '' }}>Full Width</option>
                        <option value="blank" {{ old('template', $page->template) == 'blank' ? 'selected' : '' }}>Blank (Raw HTML)</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="order" class="block text-sm font-semibold text-gray-800 mb-1">Order</label>
                    <input type="number" id="order" name="order" value="{{ old('order', $page->order) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                </div>

                <h3 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 mt-6">Publishing</h3>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-semibold text-gray-800 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-semibold text-gray-800 mb-1">Category</label>
                    <select id="category_id" name="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="">-- No Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $page->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/30">
                    Save Changes
                </button>
            </div>
        </div>
        @endif
    </form>

    @if($page->template !== 'homepage')
    <style>
        /* Hide TinyMCE API Key Warning */
        .tox-notifications-container { display: none !important; }
    </style>
    <!-- TinyMCE Classic Editor Setup -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#content',
                height: 600,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'link image media table | removeformat | fullscreen code help',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px; color: #374151; }',
                extended_valid_elements: "svg[*],use[*],path[*]",
                custom_elements: "svg,path,use",
                promotion: false,
                branding: false
            });
        });
    </script>
    @endif

    @if($page->template === 'homepage')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const checks = document.querySelectorAll('.category-check');
        const countEl = document.getElementById('cat-count');

        function updateLabels() {
            let count = 0;
            checks.forEach(function(chk) {
                const label = chk.closest('.category-label');
                if (chk.checked) {
                    count++;
                    label.classList.add('border-brand-400', 'bg-brand-50');
                    label.classList.remove('border-gray-200', 'hover:border-brand-300', 'hover:bg-gray-50');
                } else {
                    label.classList.remove('border-brand-400', 'bg-brand-50');
                    label.classList.add('border-gray-200', 'hover:border-brand-300', 'hover:bg-gray-50');
                }
            });
            if (countEl) countEl.textContent = count;
        }

        checks.forEach(function(chk) {
            chk.addEventListener('change', updateLabels);
        });
    });
    </script>
    @endif
</x-layouts.dashboard>
