<x-layouts.dashboard title="Categories Management">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Categories</h2>
            <p class="text-sm text-gray-500 mt-1">Manage categories for your pages and posts.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('superuser.categories.create') }}" class="bg-brand-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition flex items-center space-x-2 inline-flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                <span>Add Category</span>
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif

    @if($categories->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-sm">
                            <th class="py-4 px-6 font-semibold text-gray-600">Category</th>
                            <th class="py-4 px-6 font-semibold text-gray-600">Slug</th>
                            <th class="py-4 px-6 font-semibold text-gray-600">Type</th>
                            <th class="py-4 px-6 font-semibold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200" alt="{{ $category->name }}">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-900">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-500 text-sm">{{ $category->slug }}</td>
                                <td class="py-4 px-6 text-gray-500 text-sm">{{ $category->type ?? '-' }}</td>
                                <td class="py-4 px-6 text-right space-x-3">
                                    <a href="{{ route('superuser.categories.edit', $category) }}" class="text-brand-600 hover:text-brand-800 font-medium text-sm inline-flex items-center">Edit</a>
                                    <form action="{{ route('superuser.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm inline-flex items-center">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">No categories found</h3>
            <p class="text-gray-500 mt-1">Create a category to organize your content.</p>
        </div>
    @endif
</x-layouts.dashboard>
