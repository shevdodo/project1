@props([
    'name'        => 'image',
    'label'       => 'Image',
    'current'     => null,
    'previewSize' => 'md', // sm | md | lg
])

@php
    $inputId   = 'media-picker-' . $name . '-' . uniqid();
    $modalId   = 'media-modal-' . $name . '-' . uniqid();
    $previewId = 'preview-' . $name . '-' . uniqid();
    $hiddenId  = 'hidden-' . $name . '-' . uniqid();

    $sizes = [
        'sm' => 'w-16 h-16',
        'md' => 'w-24 h-24',
        'lg' => 'w-32 h-32',
    ];
    $previewClass = $sizes[$previewSize] ?? $sizes['md'];

    // Resolve current image URL
    $currentUrl = null;
    if ($current) {
        $currentUrl = Str::startsWith($current, ['http://', 'https://'])
            ? $current
            : asset('storage/' . $current);
    }
@endphp

<div data-media-picker
    x-data="{
    hasImage: {{ $currentUrl ? 'true' : 'false' }},
    previewUrl: '{{ $currentUrl ?? '' }}',
    hiddenValue: '{{ $current ?? '' }}',
    inputMode: '{{ $current ? 'library' : 'upload' }}',
    setMedia(url, path) {
        this.previewUrl = url;
        this.hiddenValue = path;
        this.hasImage = true;
        this.inputMode = 'library';
        document.getElementById('{{ $hiddenId }}').value = path;
        document.getElementById('{{ $inputId }}').value = '';
    },
    clearImage() {
        this.previewUrl = '';
        this.hiddenValue = '';
        this.hasImage = false;
        this.inputMode = 'upload';
        document.getElementById('{{ $hiddenId }}').value = '';
        document.getElementById('{{ $inputId }}').value = '';
    },
    onFileChange(e) {
        const file = e.target.files[0];
        if (file) {
            this.previewUrl = URL.createObjectURL(file);
            this.hasImage = true;
            this.inputMode = 'upload';
            this.hiddenValue = '';
            document.getElementById('{{ $hiddenId }}').value = '';
        }
    }
}" @media-selected="setMedia($event.detail.url, $event.detail.path)">
    <label class="block text-sm font-semibold text-gray-800 mb-2">{{ $label }}</label>

    {{-- Preview --}}
    <div class="flex items-start gap-4 flex-wrap">
        <div class="{{ $previewClass }} rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0 relative group">
            <template x-if="hasImage">
                <img :src="previewUrl" class="w-full h-full object-cover" alt="Preview">
            </template>
            <template x-if="!hasImage">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </template>
            {{-- Clear button --}}
            <template x-if="hasImage">
                <button type="button" @click="clearImage()"
                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity shadow">
                    ✕
                </button>
            </template>
        </div>

        <div class="flex flex-col gap-2 justify-center">
            {{-- Upload file --}}
            <label class="cursor-pointer inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Upload Baru
                <input type="file" id="{{ $inputId }}" name="{{ $name }}" accept="image/*"
                    class="sr-only" @change="onFileChange($event)">
            </label>

            {{-- Pick from library --}}
            <button type="button"
                @click="$dispatch('open-media-modal', { modalId: '{{ $modalId }}' })"
                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 text-sm font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Pilih dari Media
            </button>

            <p x-show="hasImage && inputMode === 'library'" class="text-xs text-emerald-600 font-medium">
                ✓ Dipilih dari media library
            </p>
        </div>
    </div>

    {{-- Hidden field for media library path --}}
    <input type="hidden" id="{{ $hiddenId }}" name="{{ $name }}_media_path" :value="hiddenValue">

    {{-- Media Library Modal --}}
    <div id="{{ $modalId }}"
        x-data="mediaPickerModal('{{ $modalId }}')"
        x-show="open"
        @open-media-modal.window="if ($event.detail.modalId === '{{ $modalId }}') { open = true; loadMedia(); }"
        @keydown.escape.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm"
        style="display:none">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 max-h-[85vh] flex flex-col overflow-hidden">
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <h3 class="font-bold text-gray-900 text-lg">Media Library</h3>
                <div class="flex items-center gap-3">
                    <input x-model="search" @input.debounce.400ms="loadMedia()"
                        type="text" placeholder="Cari gambar..."
                        class="text-sm border-gray-300 rounded-lg px-3 py-1.5 focus:border-brand-500 focus:ring focus:ring-brand-500/20">
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Media Grid --}}
            <div class="flex-1 overflow-y-auto p-6">
                <template x-if="loading">
                    <div class="flex items-center justify-center h-48">
                        <svg class="animate-spin w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                </template>

                <template x-if="!loading && files.length === 0">
                    <div class="text-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="font-medium">Belum ada gambar di media library</p>
                    </div>
                </template>

                <div x-show="!loading && files.length > 0"
                    class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                    <template x-for="file in files" :key="file.path">
                        <button type="button"
                            @click="selectFile(file)"
                            :class="selected && selected.path === file.path ? 'ring-2 ring-brand-500 ring-offset-2' : 'hover:ring-2 hover:ring-gray-300 hover:ring-offset-1'"
                            class="relative aspect-square rounded-lg overflow-hidden bg-gray-100 transition group">
                            <img :src="file.url" :alt="file.name" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                            <template x-if="selected && selected.path === file.path">
                                <div class="absolute top-1 right-1 bg-brand-500 rounded-full w-5 h-5 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </template>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50">
                <p x-show="selected" class="text-sm text-gray-600 truncate max-w-xs" x-text="selected ? selected.name : ''"></p>
                <div class="flex gap-3 ml-auto">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 font-medium transition">
                        Batal
                    </button>
                    <button type="button" @click="confirmSelect()"
                        :disabled="!selected"
                        :class="selected ? 'bg-brand-600 hover:bg-brand-700 text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                        class="px-5 py-2 text-sm font-semibold rounded-xl transition shadow-sm">
                        Pilih Gambar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof window.mediaPickerModal === 'undefined') {
        window.mediaPickerModal = function(modalId) {
            return {
                open: false,
                loading: false,
                files: [],
                selected: null,
                search: '',

                loadMedia() {
                    this.loading = true;
                    this.selected = null;
                    const querySearch = this.search || '';
                    fetch(`{{ route('superuser.media.api') }}?type=image&search=${encodeURIComponent(querySearch)}`)
                        .then(r => r.json())
                        .then(data => {
                            this.files = data.files || [];
                            this.loading = false;
                        })
                        .catch(() => { this.loading = false; });
                },

                selectFile(file) {
                    this.selected = file;
                },

                confirmSelect() {
                    if (!this.selected) return;
                    
                    // Dispatch CustomEvent to root picker
                    const modalEl = document.getElementById(modalId);
                    if (modalEl) {
                        modalEl.dispatchEvent(
                            new CustomEvent('media-selected', {
                                detail: { url: this.selected.url, path: this.selected.path },
                                bubbles: true,
                                composed: true
                            })
                        );
                    }
                    this.open = false;
                }
            }
        }
    }
</script>
