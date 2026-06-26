<x-layouts.dashboard title="Edit Product">
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('superuser.products.index') }}" class="hover:text-brand-600 transition">Products</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit Product</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Product: {{ $product->name }}</h2>
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

    <form action="{{ route('superuser.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-8">
        @csrf
        @method('PUT')
        
        <!-- Main Content Column -->
        <div class="w-full lg:w-2/3 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">Product Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition" required>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-800 mb-1">Description</label>
                    <textarea name="description" id="description" rows="10" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h3 class="font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">Pricing & Inventory</h3>
                <!-- Price -->
                <div class="mb-5">
                    <label for="price" class="block text-sm font-semibold text-gray-800 mb-1">Price</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                        <input type="number" name="price" id="price" value="{{ old('price', (int)$product->price) }}" min="0" step="1" class="w-full pl-12 border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 py-2.5 transition">
                    </div>
                </div>

                <!-- Weight -->
                <div class="mb-5">
                    <label for="weight" class="block text-sm font-semibold text-gray-800 mb-1">Berat Produk</label>
                    <div class="relative">
                        <input type="number" name="weight" id="weight" value="{{ old('weight', $product->weight) }}" min="0" step="1"
                            class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 pr-14 transition"
                            placeholder="0">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">gram</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Digunakan untuk kalkulasi ongkir. Kosongkan jika tidak ada.</p>
                </div>

                <!-- Stock -->
                <div class="mb-5">
                    <label for="stock" class="block text-sm font-semibold text-gray-800 mb-1">Stok Produk</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" step="1"
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition"
                        placeholder="0">
                    <p class="text-xs text-gray-400 mt-1">Stok saat ini. Pembeli hanya bisa memesan jika stok tersedia.</p>
                </div>

                <!-- Sizes -->
                <div>
                    <label for="sizes" class="block text-sm font-semibold text-gray-800 mb-1">Pilihan Ukuran / Size (Pisahkan dengan koma)</label>
                    <input type="text" name="sizes" id="sizes" value="{{ old('sizes', $product->sizes) }}"
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition"
                        placeholder="Contoh: S, M, L, XL, XXL">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika produk tidak memiliki pilihan ukuran.</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="w-full lg:w-1/3 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">Publish Options</h3>
                
                <!-- Status -->
                <div class="mb-5">
                    <label for="status" class="block text-sm font-semibold text-gray-800 mb-1">Status</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">
                        <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ old('status', $product->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>

                <!-- Category -->
                <div class="mb-5">
                    <label for="category_id" class="block text-sm font-semibold text-gray-800 mb-1">Category</label>
                    <select name="category_id" id="category_id" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Slug -->
                <div class="mb-5">
                    <label for="slug" class="block text-sm font-semibold text-gray-800 mb-1">URL Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition text-sm">
                </div>

                <!-- Product Image -->
                <div class="mb-5">
                    <x-media-picker name="image" label="Product Image" :current="old('image', $product->image)" preview-size="lg" />
                </div>

                <div class="pt-4 mt-2 border-t border-gray-100 flex flex-col space-y-3">
                    <button type="submit" class="w-full py-2.5 bg-brand-600 text-white font-medium rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition text-sm">Update Product</button>
                    <a href="{{ route('superuser.products.index') }}" class="w-full py-2.5 bg-gray-50 text-gray-700 font-medium rounded-xl hover:bg-gray-100 border border-gray-200 transition text-sm text-center">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.dashboard>
