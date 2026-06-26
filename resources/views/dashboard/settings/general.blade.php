<x-layouts.dashboard title="General Settings">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">General Settings</h2>
            <p class="text-sm text-gray-500 mt-1">
                Manage the main configuration for your site.
            </p>
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

    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm max-w-4xl">
        <form method="POST" action="{{ route('superuser.settings.general.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Site Title --}}
            <div>
                <label for="site_title" class="block text-sm font-semibold text-gray-800 mb-1">Site Title</label>
                <input type="text" id="site_title" name="site_title" value="{{ old('site_title', $settings['site_title'] ?? config('app.name')) }}" class="w-full sm:max-w-md border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
            </div>

            {{-- Tagline --}}
            <div>
                <label for="tagline" class="block text-sm font-semibold text-gray-800 mb-1">Tagline</label>
                <p class="text-xs text-gray-500 mb-2">In a few words, explain what this site is about.</p>
                <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $settings['tagline'] ?? 'Just another Laravel site') }}" class="w-full sm:max-w-md border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
            </div>

            {{-- Site Icon --}}
            <div>
                <x-media-picker
                    name="site_icon"
                    label="Site Icon"
                    :current="$settings['site_icon'] ?? null"
                    preview-size="md"
                />
                <p class="text-xs text-gray-500 mt-1">Digunakan sebagai logo utama website.</p>
            </div>

            {{-- Site Icon Shape --}}
            @php $currentIconShape = old('site_icon_shape', $settings['site_icon_shape'] ?? 'square'); @endphp
            <div>
                <label for="site_icon_shape" class="block text-sm font-semibold text-gray-800 mb-1">Site Icon Shape</label>
                <select id="site_icon_shape" name="site_icon_shape" class="w-full sm:max-w-xs border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                    <option value="square" {{ $currentIconShape == 'square' ? 'selected' : '' }}>Square</option>
                    <option value="rectangle" {{ $currentIconShape == 'rectangle' ? 'selected' : '' }}>Rectangle (Persegi panjang)</option>
                    <option value="circle" {{ $currentIconShape == 'circle' ? 'selected' : '' }}>Circle</option>
                </select>
            </div>

            {{-- Fav Icon --}}
            <div>
                <x-media-picker
                    name="fav_icon"
                    label="Fav Icon"
                    :current="$settings['fav_icon'] ?? null"
                    preview-size="sm"
                />
                <p class="text-xs text-gray-500 mt-1">Gambar kecil persegi sebagai favicon browser.</p>
            </div>

            <hr class="border-gray-100">

            {{-- Administration Email Address --}}
            <div>
                <label for="admin_email" class="block text-sm font-semibold text-gray-800 mb-1">Administration Email Address</label>
                <p class="text-xs text-gray-500 mb-2">This address is used for admin purposes. If you change this, we will send you an email at your new address to confirm it.</p>
                <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email', $settings['admin_email'] ?? Auth::user()->email) }}" class="w-full sm:max-w-md border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2">
            </div>

            {{-- Membership --}}
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Membership</label>
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="membership" {{ old('membership', $settings['membership'] ?? 'on') == 'on' ? 'checked' : '' }} class="rounded border-gray-300 text-brand-600 focus:ring-brand-500 shadow-sm w-4 h-4">
                    <span class="text-sm text-gray-700">Anyone can register</span>
                </label>
            </div>

            {{-- New User Default Role --}}
            @php $currentRole = old('default_role', $settings['default_role'] ?? 'user'); @endphp
            <div>
                <label for="default_role" class="block text-sm font-semibold text-gray-800 mb-1">New User Default Role</label>
                <select id="default_role" name="default_role" class="w-full sm:max-w-xs border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                    <option value="user" {{ $currentRole == 'user' ? 'selected' : '' }}>User</option>
                    <option value="superuser" {{ $currentRole == 'superuser' ? 'selected' : '' }}>Superuser</option>
                </select>
            </div>

            <hr class="border-gray-100">

            {{-- Timezone --}}
            @php $currentTimezone = old('timezone', $settings['timezone'] ?? 'UTC'); @endphp
            <div>
                <label for="timezone" class="block text-sm font-semibold text-gray-800 mb-1">Timezone</label>
                <p class="text-xs text-gray-500 mb-2">Choose a city in the same timezone as you.</p>
                <select id="timezone" name="timezone" class="w-full sm:max-w-xs border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                    <option value="UTC" {{ $currentTimezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                    <option value="Asia/Jakarta" {{ $currentTimezone == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                    <option value="America/New_York" {{ $currentTimezone == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                    <option value="Europe/London" {{ $currentTimezone == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                </select>
            </div>

            {{-- Date Format --}}
            @php $currentDateFormat = old('date_format', $settings['date_format'] ?? 'F j, Y'); @endphp
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-3">Date Format</label>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="date_format" value="F j, Y" {{ $currentDateFormat == 'F j, Y' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500 w-4 h-4 border-gray-300">
                        <span class="text-sm text-gray-700">June 24, 2026 (F j, Y)</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="date_format" value="Y-m-d" {{ $currentDateFormat == 'Y-m-d' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500 w-4 h-4 border-gray-300">
                        <span class="text-sm text-gray-700">2026-06-24 (Y-m-d)</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="date_format" value="m/d/Y" {{ $currentDateFormat == 'm/d/Y' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500 w-4 h-4 border-gray-300">
                        <span class="text-sm text-gray-700">06/24/2026 (m/d/Y)</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="date_format" value="d/m/Y" {{ $currentDateFormat == 'd/m/Y' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500 w-4 h-4 border-gray-300">
                        <span class="text-sm text-gray-700">24/06/2026 (d/m/Y)</span>
                    </label>
                </div>
            </div>

            {{-- Time Format --}}
            @php $currentTimeFormat = old('time_format', $settings['time_format'] ?? 'g:i a'); @endphp
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-3">Time Format</label>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="time_format" value="g:i a" {{ $currentTimeFormat == 'g:i a' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500 w-4 h-4 border-gray-300">
                        <span class="text-sm text-gray-700">1:41 pm (g:i a)</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="time_format" value="g:i A" {{ $currentTimeFormat == 'g:i A' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500 w-4 h-4 border-gray-300">
                        <span class="text-sm text-gray-700">1:41 PM (g:i A)</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="time_format" value="H:i" {{ $currentTimeFormat == 'H:i' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500 w-4 h-4 border-gray-300">
                        <span class="text-sm text-gray-700">13:41 (H:i)</span>
                    </label>
                </div>
            </div>

            {{-- Week Starts On --}}
            @php $currentWeekStart = old('week_start', $settings['week_start'] ?? '1'); @endphp
            <div>
                <label for="week_start" class="block text-sm font-semibold text-gray-800 mb-1">Week Starts On</label>
                <select id="week_start" name="week_start" class="w-full sm:max-w-xs border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 bg-white">
                    <option value="0" {{ $currentWeekStart == '0' ? 'selected' : '' }}>Sunday</option>
                    <option value="1" {{ $currentWeekStart == '1' ? 'selected' : '' }}>Monday</option>
                    <option value="2" {{ $currentWeekStart == '2' ? 'selected' : '' }}>Tuesday</option>
                    <option value="3" {{ $currentWeekStart == '3' ? 'selected' : '' }}>Wednesday</option>
                    <option value="4" {{ $currentWeekStart == '4' ? 'selected' : '' }}>Thursday</option>
                    <option value="5" {{ $currentWeekStart == '5' ? 'selected' : '' }}>Friday</option>
                    <option value="6" {{ $currentWeekStart == '6' ? 'selected' : '' }}>Saturday</option>
                </select>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-start">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/30">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
