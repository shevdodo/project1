<x-layouts.dashboard title="Menu Management">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Menus</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your website's navigation menus.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm font-medium">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 flex items-center justify-between">
        <form action="{{ route('superuser.menus.index') }}" method="GET" class="flex items-center space-x-4">
            <label for="menu-select" class="font-medium text-gray-700">Select a menu to edit:</label>
            <select id="menu-select" name="menu" class="border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2" onchange="this.form.submit()">
                @if($menus->isEmpty())
                    <option value="">-- No menus available --</option>
                @endif
                @foreach($menus as $m)
                    <option value="{{ $m->id }}" {{ $currentMenu && $currentMenu->id == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">Select</button>
        </form>
        
        <div class="flex items-center space-x-2 text-sm">
            <span class="text-gray-500">or</span>
            <button onclick="document.getElementById('create-menu-modal').classList.remove('hidden')" class="text-brand-600 font-medium hover:text-brand-800">create a new menu</button>
        </div>
    </div>

    @if($currentMenu)
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Add Menu Items -->
        <div class="w-full lg:w-1/3 space-y-4">
            <h3 class="font-bold text-lg text-gray-900 mb-2">Add Menu Items</h3>
            
            <!-- Custom Link Accordion -->
            <div x-data="{ open: true }" class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                <button @click="open = !open" class="w-full px-5 py-4 text-left font-semibold text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center">
                    <span>Custom Links</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" class="p-5 border-t border-gray-100 space-y-4">
                    <form action="{{ route('superuser.menus.items.add', $currentMenu) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="custom">
                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 mb-1">URL</label>
                            <input type="text" name="url" placeholder="e.g. /about or https://" class="w-full text-sm border-gray-300 rounded-lg" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 mb-1">Link Text</label>
                            <input type="text" name="title" placeholder="Menu Item" class="w-full text-sm border-gray-300 rounded-lg" required>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm rounded-lg font-medium transition">Add to Menu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pages Accordion -->
            <div x-data="{ open: false }" class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                <button @click="open = !open" class="w-full px-5 py-4 text-left font-semibold text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center">
                    <span>Pages</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" class="p-5 border-t border-gray-100 space-y-4" style="display: none;">
                    <form action="{{ route('superuser.menus.items.add', $currentMenu) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="page">
                        <div class="max-h-40 overflow-y-auto mb-4 border border-gray-200 rounded-lg p-2 space-y-2">
                            @forelse($pages as $page)
                                <label class="flex items-center space-x-2 text-sm p-1 hover:bg-gray-50 rounded">
                                    <input type="radio" name="reference_id" value="{{ $page->id }}" class="text-brand-600 focus:ring-brand-500" required onchange="document.getElementById('page_title').value = '{{ addslashes($page->title) }}'">
                                    <span>{{ $page->title }}</span>
                                </label>
                            @empty
                                <p class="text-xs text-gray-500 p-2">No pages found.</p>
                            @endforelse
                        </div>
                        <input type="hidden" name="title" id="page_title" value="">
                        <div class="text-right">
                            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm rounded-lg font-medium transition" {{ $pages->isEmpty() ? 'disabled' : '' }}>Add to Menu</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Categories Accordion -->
            <div x-data="{ open: false }" class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                <button @click="open = !open" class="w-full px-5 py-4 text-left font-semibold text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center">
                    <span>Categories</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" class="p-5 border-t border-gray-100 space-y-4" style="display: none;">
                    <form action="{{ route('superuser.menus.items.add', $currentMenu) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="category">
                        <div class="max-h-40 overflow-y-auto mb-4 border border-gray-200 rounded-lg p-2 space-y-2">
                            @forelse($categories as $category)
                                <label class="flex items-center space-x-2 text-sm p-1 hover:bg-gray-50 rounded">
                                    <input type="radio" name="reference_id" value="{{ $category->id }}" class="text-brand-600 focus:ring-brand-500" required onchange="document.getElementById('category_title').value = '{{ addslashes($category->name) }}'">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @empty
                                <p class="text-xs text-gray-500 p-2">No categories found.</p>
                            @endforelse
                        </div>
                        <input type="hidden" name="title" id="category_title" value="">
                        <div class="text-right">
                            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm rounded-lg font-medium transition" {{ $categories->isEmpty() ? 'disabled' : '' }}>Add to Menu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Menu Structure -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-gray-900">Menu Structure: {{ $currentMenu->name }}</h3>
                    <div class="flex space-x-3 items-center">
                        <form action="{{ route('superuser.menus.structure.save', $currentMenu) }}" method="POST" id="save-structure-form">
                            @csrf
                            <input type="hidden" name="structure" id="structure-input" value="">
                            <button type="button" onclick="saveMenuStructure()" class="px-4 py-2 bg-brand-600 text-white rounded-lg font-medium hover:bg-brand-700 transition text-sm shadow-sm">Save Structure</button>
                        </form>
                        <form action="{{ route('superuser.menus.destroy', $currentMenu) }}" method="POST" onsubmit="return confirm('Delete this menu?')">
                            @csrf @method('DELETE')
                            <button class="text-sm text-red-600 hover:underline font-medium px-2">Delete Menu</button>
                        </form>
                    </div>
                </div>
                <div class="p-6 bg-gray-50/50 min-h-[300px]">
                    <p class="text-sm text-gray-500 mb-4">Add items from the column on the left.</p>
                    
                    <div class="space-y-3" id="menu-items-list">
                        @forelse($currentMenu->parentItems as $item)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex items-center justify-between cursor-move group" data-id="{{ $item->id }}">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $item->title }}</p>
                                        <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">{{ $item->type }} @if($item->type == 'custom') ({{ $item->url }}) @endif</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                            onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->title) }}', '{{ $item->url }}', '{{ $item->type }}')"
                                            class="text-blue-500 hover:text-blue-700 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </button>
                                    <form action="{{ route('superuser.menus.items.destroy', $item) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 p-2" onclick="return confirm('Delete this item?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-xl">
                                <p class="text-gray-500 font-medium">This menu is empty.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Create Menu Modal -->
    <div id="create-menu-modal" class="fixed inset-0 bg-gray-900/50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-900">Create New Menu</h3>
                <button onclick="document.getElementById('create-menu-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('superuser.menus.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Menu Name</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 px-4 py-2" placeholder="e.g. Main Navigation" required>
                </div>
                <div class="text-right">
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 text-white font-medium rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition">Create Menu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="edit-item-modal" class="fixed inset-0 bg-gray-900/50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-900">Edit Menu Item</h3>
                <button onclick="document.getElementById('edit-item-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="edit-item-form" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Link Text</label>
                    <input type="text" name="title" id="edit-item-title" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 px-4 py-2" required>
                </div>
                <div class="mb-4" id="edit-item-url-container">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">URL</label>
                    <input type="text" name="url" id="edit-item-url" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 px-4 py-2">
                </div>
                <div class="text-right">
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 text-white font-medium rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition">Update Item</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        // Base URL for menu item routes (generated by Laravel, environment-safe)
        const menuItemBaseUrl = "{{ route('superuser.menus.items.update', ['item' => '__ID__']) }}".replace('__ID__', '');

        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('menu-items-list');
            if (el) {
                var sortable = Sortable.create(el, {
                    animation: 150,
                    handle: '.cursor-move',
                    ghostClass: 'bg-gray-100'
                });
            }
        });

        function saveMenuStructure() {
            var el = document.getElementById('menu-items-list');
            if (!el) return;
            
            var items = el.querySelectorAll('[data-id]');
            var structure = [];
            
            items.forEach(function(item) {
                structure.push({ id: item.dataset.id, children: [] });
            });
            
            document.getElementById('structure-input').value = JSON.stringify(structure);
            document.getElementById('save-structure-form').submit();
        }

        function openEditModal(id, title, url, type) {
            document.getElementById('edit-item-modal').classList.remove('hidden');
            document.getElementById('edit-item-title').value = title;
            
            let urlContainer = document.getElementById('edit-item-url-container');
            let urlInput = document.getElementById('edit-item-url');

            if (type === 'custom') {
                urlContainer.classList.remove('hidden');
                urlInput.value = url;
            } else {
                urlContainer.classList.add('hidden');
                urlInput.value = '';
            }

            document.getElementById('edit-item-form').action = menuItemBaseUrl + id;
        }
    </script>
</x-layouts.dashboard>
