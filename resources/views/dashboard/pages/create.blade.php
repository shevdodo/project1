<x-layouts.dashboard title="Create Page">
    <div class="mb-8">
        <a href="{{ route('superuser.pages.index') }}" class="text-sm text-brand-600 hover:text-brand-800 flex items-center space-x-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span>Back to Pages</span>
        </a>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Create New Page</h2>
        <p class="text-sm text-gray-500 mt-1">Write your content and set visibility.</p>
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

    <form method="POST" action="{{ route('superuser.pages.store') }}" class="flex flex-col lg:flex-row gap-8">
        @csrf

        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-1">Page Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2" placeholder="e.g. About Us">
                </div>

                <div class="mb-6">
                    <label for="slug" class="block text-sm font-semibold text-gray-800 mb-1">Slug</label>
                    <p class="text-xs text-gray-500 mb-2">Leave blank to auto-generate from title.</p>
                    <div class="flex items-center">
                        <span class="bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg px-3 py-2 text-gray-500 text-sm">{{ url('/') }}/</span>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="flex-1 border-gray-300 rounded-r-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2" placeholder="about-us">
                    </div>
                </div>

                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-800 mb-1">Page Content</label>
                    <textarea id="content" name="content" rows="12" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">{{ old('content') }}</textarea>
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
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="template" class="block text-sm font-semibold text-gray-800 mb-1">Template</label>
                    <select id="template" name="template" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="default" {{ old('template') == 'default' ? 'selected' : '' }}>Default Template</option>
                        <option value="full-width" {{ old('template') == 'full-width' ? 'selected' : '' }}>Full Width</option>
                        <option value="blank" {{ old('template') == 'blank' ? 'selected' : '' }}>Blank (Raw HTML)</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="order" class="block text-sm font-semibold text-gray-800 mb-1">Order</label>
                    <input type="number" id="order" name="order" value="{{ old('order', 0) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
                </div>

                <h3 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 mt-6">Publishing</h3>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-semibold text-gray-800 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-semibold text-gray-800 mb-1">Category</label>
                    <select id="category_id" name="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                        <option value="">-- No Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/30">
                    Save Page
                </button>
            </div>
        </div>
    </form>

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
</x-layouts.dashboard>
