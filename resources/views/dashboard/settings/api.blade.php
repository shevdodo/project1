<x-layouts.dashboard title="API Settings">
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">API Settings</h2>
        <p class="text-sm text-gray-500 mt-1">Manage external API integrations for shipping and payments.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('superuser.settings.api.update') }}">
        @csrf
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm mb-6 space-y-8 max-w-3xl">
            
            <!-- Shipping API -->
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">API Ongkos Kirim (Shipping)</h3>
                    <p class="text-sm text-gray-500 max-w-md">Enable this to automatically calculate shipping costs based on the customer's location using a third-party shipping API (e.g. RajaOngkir).</p>
                </div>
                <div class="ml-4 pt-1">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="api_shipping_enabled" value="1" class="sr-only peer" {{ (isset($settings['api_shipping_enabled']) && $settings['api_shipping_enabled'] == '1') ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-brand-600"></div>
                    </label>
                </div>
            </div>
            
            <div class="mt-4">
                <label for="api_shipping_key" class="block text-sm font-semibold text-gray-800 mb-1">Shipping API Key</label>
                <input type="text" id="api_shipping_key" name="api_shipping_key" value="{{ old('api_shipping_key', $settings['api_shipping_key'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 font-mono text-sm" placeholder="Enter your Shipping API Key (e.g., RajaOngkir API Key)">
            </div>

            <hr class="border-gray-100">

            <!-- Payment API -->
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">API Payment Gateway</h3>
                    <p class="text-sm text-gray-500 max-w-md">Enable this to accept automated online payments (Credit Card, Virtual Account, e-Wallet) via a Payment Gateway (e.g. Midtrans, Xendit).</p>
                </div>
                <div class="ml-4 pt-1">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="api_payment_enabled" value="1" class="sr-only peer" {{ (isset($settings['api_payment_enabled']) && $settings['api_payment_enabled'] == '1') ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-brand-600"></div>
                    </label>
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <div>
                    <label for="api_payment_server_key" class="block text-sm font-semibold text-gray-800 mb-1">Server Key (Secret Key)</label>
                    <input type="text" id="api_payment_server_key" name="api_payment_server_key" value="{{ old('api_payment_server_key', $settings['api_payment_server_key'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 font-mono text-sm" placeholder="Enter Server Key">
                </div>
                <div>
                    <label for="api_payment_client_key" class="block text-sm font-semibold text-gray-800 mb-1">Client Key (Public Key)</label>
                    <input type="text" id="api_payment_client_key" name="api_payment_client_key" value="{{ old('api_payment_client_key', $settings['api_payment_client_key'] ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-500/20 px-4 py-2 font-mono text-sm" placeholder="Enter Client Key">
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-brand-600 text-white px-6 py-3 rounded-xl font-medium shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition">
                    Save API Settings
                </button>
            </div>
        </div>
    </form>
</x-layouts.dashboard>
