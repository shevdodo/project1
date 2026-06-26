<x-layouts.dashboard title="Products Management">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Products</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your catalog and inventory.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('superuser.products.create') }}" class="bg-brand-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition flex items-center space-x-2 inline-flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('superuser.products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-grow">
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
            </div>
            <div class="w-full sm:w-48">
                <select name="category_id" class="w-full py-2 pl-3 pr-8 border border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 text-gray-700">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-900 transition whitespace-nowrap">Filter</button>
            @if(request('search') || request('category_id'))
                <a href="{{ route('superuser.products.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition text-center whitespace-nowrap">Clear</a>
            @endif
        </form>
    </div>
    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif

    @if($products->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-sm">
                            <th class="py-4 px-6 font-semibold text-gray-600">Product</th>
                            <th class="py-4 px-6 font-semibold text-gray-600">Price</th>
                            <th class="py-4 px-6 font-semibold text-gray-600">Category</th>
                            <th class="py-4 px-6 font-semibold text-gray-600">Status</th>
                            <th class="py-4 px-6 font-semibold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200" alt="{{ $product->name }}">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $product->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-800 font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-gray-500 text-sm">{{ $product->category ? $product->category->name : '-' }}</td>
                                <td class="py-4 px-6 text-sm">
                                    @if($product->status == 'available')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-medium">Available</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Unavailable</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right space-x-3">
                                    <a href="{{ route('superuser.products.edit', $product) }}" class="text-brand-600 hover:text-brand-800 font-medium text-sm inline-flex items-center">Edit</a>
                                    <form action="{{ route('superuser.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
            @if($products->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">No products found</h3>
            <p class="text-gray-500 mt-1">Start adding products to your catalog.</p>
        </div>
    @endif
</x-layouts.dashboard>
