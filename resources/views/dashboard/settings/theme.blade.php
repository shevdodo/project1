<x-layouts.dashboard>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Theme Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('superuser.settings.theme.update') }}" method="POST" class="space-y-6 max-w-2xl">
                        @csrf
                        
                        <!-- Theme Tone Color -->
                        <div>
                            <label for="theme_color" class="block text-sm font-medium text-gray-700">Tone Warna (Theme Color)</label>
                            <p class="text-sm text-gray-500 mb-2">Pilih palet warna utama untuk situs Anda.</p>
                            <select name="theme_color" id="theme_color" class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">
                                <option value="indigo" {{ (isset($settings['theme_color']) && $settings['theme_color'] == 'indigo') ? 'selected' : '' }}>Indigo (Default Biru Elegan)</option>
                                <option value="brown" {{ (isset($settings['theme_color']) && $settings['theme_color'] == 'brown') ? 'selected' : '' }}>Brown (Cokelat Klasik / Batik)</option>
                                <option value="emerald" {{ (isset($settings['theme_color']) && $settings['theme_color'] == 'emerald') ? 'selected' : '' }}>Emerald (Hijau Zamrud)</option>
                                <option value="rose" {{ (isset($settings['theme_color']) && $settings['theme_color'] == 'rose') ? 'selected' : '' }}>Rose (Merah Muda Merona)</option>
                            </select>
                            @error('theme_color')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="border-gray-100">

                        <!-- Font -->
                        <div>
                            <label for="theme_font" class="block text-sm font-medium text-gray-700">Pilihan Font</label>
                            <p class="text-sm text-gray-500 mb-2">Pilih gaya huruf yang akan digunakan pada seluruh situs.</p>
                            <select name="theme_font" id="theme_font" class="mt-1 block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm">
                                <option value="figtree" {{ (isset($settings['theme_font']) && $settings['theme_font'] == 'figtree') ? 'selected' : '' }}>Figtree (Modern & Clean)</option>
                                <option value="inter" {{ (isset($settings['theme_font']) && $settings['theme_font'] == 'inter') ? 'selected' : '' }}>Inter (Profesional & Terbaca)</option>
                                <option value="merriweather" {{ (isset($settings['theme_font']) && $settings['theme_font'] == 'merriweather') ? 'selected' : '' }}>Merriweather (Elegan & Klasik Serif)</option>
                                <option value="poppins" {{ (isset($settings['theme_font']) && $settings['theme_font'] == 'poppins') ? 'selected' : '' }}>Poppins (Bulat & Dinamis)</option>
                            </select>
                            @error('theme_font')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end pt-4">
                            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                                Save Theme Settings
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
