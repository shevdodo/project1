<x-layouts.dashboard title="Edit Category">
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('superuser.categories.index') }}" class="hover:text-brand-600 transition">Categories</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit Category</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Category: {{ $category->name }}</h2>
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

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 max-w-3xl">
        <form action="{{ route('superuser.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition" required>
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-800 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-800 mb-1">Type</label>
                    <select name="type" id="type" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">
                        <option value="post" {{ old('type', $category->type) == 'post' ? 'selected' : '' }}>Post</option>
                        <option value="product" {{ old('type', $category->type) == 'product' ? 'selected' : '' }}>Produk</option>
                    </select>
                </div>

                <!-- Image -->
                <div>
                    <x-media-picker name="image" label="Feature Image" :value="$category->image" preview-size="lg" />
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('superuser.categories.index') }}" class="px-5 py-2.5 text-gray-600 hover:text-gray-900 font-medium text-sm transition">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 text-white font-medium rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition text-sm">Update Category</button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
