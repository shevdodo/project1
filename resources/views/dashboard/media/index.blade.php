<x-layouts.dashboard title="Media Library">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Media Library</h2>
            <p class="text-sm text-gray-500 mt-1">Manage all uploaded images, documents, and other files.</p>
        </div>
        <button id="btn-open-upload"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white rounded-xl font-semibold shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Media
        </button>
    </div>

    {{-- Flash Status --}}
    @if(session('status'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('status') }}
    </div>
    @endif

    {{-- Upload Drop Zone (Hidden by default) --}}
    <div id="upload-zone" class="hidden mb-6">
        <form action="{{ route('superuser.media.upload') }}" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf
            <div id="drop-area"
                class="border-2 border-dashed border-brand-400 rounded-2xl p-10 text-center bg-brand-50/50 cursor-pointer hover:bg-brand-50 transition-all duration-200 relative">
                <input type="file" name="files[]" id="file-input" multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.zip" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                <div class="flex flex-col items-center gap-3 pointer-events-none">
                    <div class="w-16 h-16 rounded-2xl bg-brand-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    <div>
                        <p class="text-base font-semibold text-gray-800">Drag & drop files here</p>
                        <p class="text-sm text-gray-500 mt-1">or <span class="text-brand-600 font-medium">click to browse</span></p>
                        <p class="text-xs text-gray-400 mt-2">Images, Videos, Audio, PDF, Docs, Zip • Max 20MB per file</p>
                    </div>
                </div>
                {{-- Preview list --}}
                <div id="file-preview-list" class="mt-4 space-y-2 text-left hidden pointer-events-none"></div>
            </div>
            <div class="mt-3 flex items-center justify-between gap-3">
                <div id="upload-progress" class="flex-1 hidden">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progress-bar" class="bg-brand-600 h-2 rounded-full transition-all duration-300" style="width:0%"></div>
                    </div>
                    <p id="upload-status" class="text-xs text-gray-500 mt-1">Uploading...</p>
                </div>
                <div class="flex gap-2 ml-auto">
                    <button type="button" id="btn-cancel-upload" class="px-4 py-2 text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 text-sm font-medium transition">Cancel</button>
                    <button type="submit" id="btn-submit-upload" class="px-5 py-2 bg-brand-600 text-white rounded-xl font-semibold text-sm hover:bg-brand-700 transition shadow shadow-brand-600/30">Upload Files</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            {{-- Type Filter --}}
            <div class="flex items-center gap-2 flex-wrap">
                @foreach(['all' => 'All', 'image' => 'Images', 'video' => 'Videos', 'audio' => 'Audio', 'document' => 'Documents'] as $key => $label)
                <a href="{{ route('superuser.media.index', array_merge(request()->query(), ['type' => $key])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                   {{ $type === $key ? 'bg-brand-600 text-white shadow-sm shadow-brand-600/30' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
            {{-- Search --}}
            <form method="GET" action="{{ route('superuser.media.index') }}" class="flex gap-2 ml-auto w-full sm:w-auto">
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search files..."
                    class="w-full sm:w-56 border-gray-200 rounded-xl px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500/20 focus:ring">
                <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Media Grid --}}
    @if(count($files) > 0)
    <div id="media-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
        @foreach($files as $file)
        <div class="media-item group relative bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden cursor-pointer hover:shadow-md hover:border-brand-300 transition-all duration-200"
            data-url="{{ $file['url'] }}"
            data-name="{{ $file['name'] }}"
            data-size="{{ $file['size_human'] }}"
            data-date="{{ $file['date'] }}"
            data-mime="{{ $file['mime'] }}"
            data-path="{{ $file['path'] }}"
            data-is-image="{{ $file['is_image'] ? 'true' : 'false' }}">

            {{-- Thumbnail --}}
            <div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                @if($file['is_image'])
                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-full object-cover" loading="lazy">
                @elseif($file['is_video'])
                    <div class="flex flex-col items-center gap-1 text-gray-400">
                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M4 8a2 2 0 012-2h9a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V8z"/></svg>
                        <span class="text-[10px] font-medium text-blue-500">VIDEO</span>
                    </div>
                @elseif($file['is_audio'])
                    <div class="flex flex-col items-center gap-1 text-gray-400">
                        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        <span class="text-[10px] font-medium text-green-500">AUDIO</span>
                    </div>
                @elseif($file['is_pdf'])
                    <div class="flex flex-col items-center gap-1">
                        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span class="text-[10px] font-medium text-red-500">PDF</span>
                    </div>
                @else
                    <div class="flex flex-col items-center gap-1">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-[10px] font-medium text-gray-500">FILE</span>
                    </div>
                @endif
            </div>

            {{-- File name --}}
            <div class="px-2 py-2">
                <p class="text-[11px] text-gray-700 truncate font-medium">{{ $file['name'] }}</p>
                <p class="text-[10px] text-gray-400">{{ $file['size_human'] }}</p>
            </div>

            {{-- Hover overlay --}}
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-200 rounded-2xl pointer-events-none"></div>
        </div>
        @endforeach
    </div>

    {{-- File count --}}
    <p class="text-xs text-gray-400 mt-4 text-center">Showing {{ count($files) }} file(s)</p>

    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
        <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">No media files found</h3>
        <p class="text-gray-400 text-sm mt-1 mb-6">Upload your first file to get started.</p>
        <button id="btn-open-upload-empty" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 text-white rounded-xl font-semibold shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Upload Media
        </button>
    </div>
    @endif

    {{-- ===== Detail Modal ===== --}}
    <div id="media-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="modal-backdrop"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden z-10 flex flex-col md:flex-row max-h-[90vh]">

            {{-- Preview --}}
            <div id="modal-preview" class="md:w-1/2 bg-gray-50 flex items-center justify-center p-6 min-h-64">
                {{-- Filled by JS --}}
            </div>

            {{-- Details --}}
            <div class="md:w-1/2 flex flex-col p-6 overflow-y-auto">
                <div class="flex items-start justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-900" id="modal-filename">File Details</h3>
                    <button id="modal-close" class="text-gray-400 hover:text-gray-700 p-1 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <dl class="space-y-3 text-sm flex-1">
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Type</dt>
                        <dd class="text-gray-800" id="modal-mime">—</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Size</dt>
                        <dd class="text-gray-800" id="modal-size">—</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Date</dt>
                        <dd class="text-gray-800" id="modal-date">—</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">File URL</dt>
                        <div class="flex gap-2 items-center mt-1">
                            <input id="modal-url" type="text" readonly
                                class="flex-1 text-xs border-gray-200 rounded-lg px-2 py-2 bg-gray-50 text-gray-700 focus:ring-0 focus:border-gray-300"
                                value="">
                            <button id="btn-copy-url"
                                class="shrink-0 px-3 py-2 bg-brand-600 text-white text-xs font-semibold rounded-lg hover:bg-brand-700 transition">
                                Copy
                            </button>
                        </div>
                        <p id="copy-success" class="text-xs text-green-600 mt-1 hidden">✓ URL copied to clipboard!</p>
                    </div>
                </dl>

                <div class="border-t border-gray-100 pt-4 mt-4 flex gap-3">
                    <a id="modal-open-link" href="#" target="_blank"
                        class="flex-1 text-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                        Open File
                    </a>
                    <button id="btn-delete-file"
                        class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl text-sm font-medium hover:bg-red-100 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ===== Upload Zone Toggle =====
        function openUpload() {
            document.getElementById('upload-zone').classList.remove('hidden');
            document.getElementById('upload-zone').scrollIntoView({ behavior: 'smooth' });
        }
        document.getElementById('btn-open-upload')?.addEventListener('click', openUpload);
        document.getElementById('btn-open-upload-empty')?.addEventListener('click', openUpload);
        document.getElementById('btn-cancel-upload')?.addEventListener('click', function () {
            document.getElementById('upload-zone').classList.add('hidden');
        });

        // ===== File input preview =====
        const fileInput = document.getElementById('file-input');
        const previewList = document.getElementById('file-preview-list');

        fileInput?.addEventListener('change', function () {
            previewList.innerHTML = '';
            previewList.classList.remove('hidden');
            Array.from(this.files).forEach(f => {
                const li = document.createElement('div');
                li.className = 'flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-100 shadow-sm text-xs text-gray-700';
                li.innerHTML = `<svg class="w-4 h-4 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="truncate flex-1">${f.name}</span>
                <span class="shrink-0 text-gray-400">${(f.size / 1024).toFixed(1)} KB</span>`;
                previewList.appendChild(li);
            });
        });

        // ===== Upload form ajax =====
        document.getElementById('upload-form')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const progressWrap = document.getElementById('upload-progress');
            const progressBar = document.getElementById('progress-bar');
            const statusText = document.getElementById('upload-status');

            progressWrap.classList.remove('hidden');
            statusText.textContent = 'Uploading...';
            progressBar.style.width = '10%';

            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                progressBar.style.width = '100%';
                statusText.textContent = data.uploaded.length + ' file(s) uploaded!';
                setTimeout(() => location.reload(), 800);
            })
            .catch(() => {
                statusText.textContent = 'Upload failed. Please try again.';
            });
        });

        // ===== Media Grid Click → Modal =====
        const modal = document.getElementById('media-modal');
        let currentPath = null;

        document.querySelectorAll('.media-item').forEach(item => {
            item.addEventListener('click', function () {
                const url = this.dataset.url;
                const name = this.dataset.name;
                const size = this.dataset.size;
                const date = this.dataset.date;
                const mime = this.dataset.mime;
                const isImage = this.dataset.isImage === 'true';
                currentPath = this.dataset.path;

                // Populate modal
                document.getElementById('modal-filename').textContent = name;
                document.getElementById('modal-mime').textContent = mime;
                document.getElementById('modal-size').textContent = size;
                document.getElementById('modal-date').textContent = date;
                document.getElementById('modal-url').value = url;
                document.getElementById('modal-open-link').href = url;
                document.getElementById('copy-success').classList.add('hidden');

                // Preview
                const preview = document.getElementById('modal-preview');
                if (isImage) {
                    preview.innerHTML = `<img src="${url}" class="max-w-full max-h-80 object-contain rounded-xl shadow" alt="${name}">`;
                } else if (mime.startsWith('video/')) {
                    preview.innerHTML = `<video src="${url}" controls class="max-w-full max-h-80 rounded-xl shadow"></video>`;
                } else if (mime.startsWith('audio/')) {
                    preview.innerHTML = `<audio src="${url}" controls class="w-full"></audio>`;
                } else {
                    preview.innerHTML = `<div class="flex flex-col items-center gap-3 text-gray-400">
                        <svg class="w-20 h-20 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm">${name}</p>
                    </div>`;
                }

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        // Close modal
        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            currentPath = null;
        }
        document.getElementById('modal-close')?.addEventListener('click', closeModal);
        document.getElementById('modal-backdrop')?.addEventListener('click', closeModal);

        // Copy URL
        document.getElementById('btn-copy-url')?.addEventListener('click', function () {
            const urlInput = document.getElementById('modal-url');
            urlInput.select();
            navigator.clipboard.writeText(urlInput.value).then(() => {
                document.getElementById('copy-success').classList.remove('hidden');
                setTimeout(() => document.getElementById('copy-success').classList.add('hidden'), 3000);
            });
        });

        // Delete file
        document.getElementById('btn-delete-file')?.addEventListener('click', function () {
            if (!currentPath) return;
            if (!confirm('Are you sure you want to permanently delete this file? This action cannot be undone.')) return;

            fetch('{{ route("superuser.media.destroy") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({ path: currentPath })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    location.reload();
                } else {
                    alert('Failed to delete: ' + (data.message || 'Unknown error'));
                }
            });
        });
    });
    </script>
    @endpush

</x-layouts.dashboard>
