<x-layouts.dashboard title="Edit Post">
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('superuser.posts.index') }}" class="hover:text-brand-600 transition">Posts</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit Post</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Post: {{ $post->title }}</h2>
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

    <form action="{{ route('superuser.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-8">
        @csrf
        @method('PUT')
        
        <!-- Main Content Column -->
        <div class="w-full lg:w-2/3 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-1">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition" required>
                </div>

                <!-- Excerpt -->
                <div class="mb-6">
                    <label for="excerpt" class="block text-sm font-semibold text-gray-800 mb-1">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="3" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-800 mb-1">Content</label>
                    <textarea name="content" id="content" rows="15" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">{{ old('content', $post->content) }}</textarea>
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
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <!-- Category -->
                <div class="mb-5">
                    <label for="category_id" class="block text-sm font-semibold text-gray-800 mb-1">Category</label>
                    <select name="category_id" id="category_id" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Slug -->
                <div class="mb-5">
                    <label for="slug" class="block text-sm font-semibold text-gray-800 mb-1">URL Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2.5 transition text-sm">
                </div>

                <!-- Featured Image -->
                <div class="mb-5">
                    <x-media-picker name="image" label="Featured Image" :current="old('image', $post->image)" preview-size="lg" />
                </div>

                <div class="pt-4 mt-2 border-t border-gray-100 flex flex-col space-y-3">
                    <button type="submit" class="w-full py-2.5 bg-brand-600 text-white font-medium rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition text-sm">Update Post</button>
                    <a href="{{ route('superuser.posts.index') }}" class="w-full py-2.5 bg-gray-50 text-gray-700 font-medium rounded-xl hover:bg-gray-100 border border-gray-200 transition text-sm text-center">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</x-layouts.dashboard>
