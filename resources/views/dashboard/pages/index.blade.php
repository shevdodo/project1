<x-layouts.dashboard title="Pages Management">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Pages</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your website's static pages.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('superuser.pages.create') }}" class="bg-brand-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition flex items-center space-x-2 inline-flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                <span>Add Page</span>
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium flex items-center space-x-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-4 font-semibold">Title</th>
                        <th class="px-6 py-4 font-semibold">Category</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Last Updated</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    {{-- ===== HOME PAGE (pinned at top) ===== --}}
                    @if(isset($homePage) && $homePage)
                    <tr class="bg-brand-50/50 hover:bg-brand-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-brand-100 text-brand-700 text-[10px] font-bold uppercase tracking-wider">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                    Homepage
                                </span>
                                <p class="font-semibold text-gray-900">{{ $homePage->title }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5 pl-0.5">/ (Halaman Beranda)</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-500 text-xs italic">—</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Published</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                            {{ $homePage->updated_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('superuser.pages.edit', $homePage) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 inline-block">Edit</a>
                            <a href="{{ url('/') }}" target="_blank" class="text-gray-400 hover:text-gray-700 inline-block text-xs">View ↗</a>
                        </td>
                    </tr>
                    @endif
                    {{-- ===== OTHER PAGES ===== --}}
                    @forelse($pages as $page)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-semibold text-gray-900">{{ $page->title }}</p>
                                <p class="text-xs text-gray-500">/{{ $page->slug }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-600">{{ $page->category ? $page->category->name : 'Uncategorized' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($page->status === 'published')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Published</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $page->updated_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('superuser.pages.edit', $page) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 inline-block">Edit</a>
                                <form action="{{ route('superuser.pages.destroy', $page) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        @if(!isset($homePage) || !$homePage)
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">No pages found</h3>
                                <p class="text-gray-500 mt-1">Get started by creating a new page.</p>
                            </td>
                        </tr>
                        @endif
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pages->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $pages->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>
