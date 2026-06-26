<x-layouts.dashboard title="My Cart">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">My Cart</h2>
            <p class="text-sm text-gray-500 mt-1">Review your items before checkout.</p>
        </div>
    </div>

    @if(count($cart) > 0)
        @php
            $totalWeight = 0;
            foreach($cart as $details) {
                $totalWeight += ($details['weight'] ?? 0) * $details['quantity'];
            }
            if ($totalWeight <= 0) {
                $totalWeight = 1000; // Fallback default
            }
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Mobile View: Cards -->
            <div class="block md:hidden divide-y divide-gray-100">
                @php $totalPriceMobile = 0; @endphp
                @foreach($cart as $id => $details)
                    @php $totalPriceMobile += $details['price'] * $details['quantity']; @endphp
                    <div class="p-4 flex gap-4 relative">
                        @if($details['image'])
                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="w-20 h-20 object-cover rounded-xl border border-gray-200 shrink-0">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 text-sm mb-1 pr-8">{{ $details['name'] }}</h4>
                            @if(!empty($details['size']))
                                <p class="text-xs text-gray-500 mb-1">Size: <span class="font-semibold text-gray-700">{{ $details['size'] }}</span></p>
                            @endif
                            <p class="font-bold text-brand-600 text-sm mb-3">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                            
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="quantity" value="{{ $details['quantity'] - 1 }}" class="w-7 h-7 flex items-center justify-center rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                </button>
                                <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-12 h-7 text-center border-gray-200 rounded-md text-sm p-0 focus:ring-brand-500 focus:border-brand-500" onchange="this.form.submit()">
                                <button type="submit" name="quantity" value="{{ $details['quantity'] + 1 }}" class="w-7 h-7 flex items-center justify-center rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </form>
                        </div>
                        <div class="absolute top-4 right-4">
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop View: Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-sm">
                            <th class="py-4 px-6 font-semibold text-gray-600">Product</th>
                            <th class="py-4 px-6 font-semibold text-gray-600 text-center">Quantity</th>
                            <th class="py-4 px-6 font-semibold text-gray-600 text-right">Price</th>
                            <th class="py-4 px-6 font-semibold text-gray-600 text-right">Total</th>
                            <th class="py-4 px-6 font-semibold text-gray-600 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $totalPrice = 0; @endphp
                        @foreach($cart as $id => $details)
                            @php $totalPrice += $details['price'] * $details['quantity']; @endphp
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-4">
                                        @if($details['image'])
                                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                        <span class="font-bold text-gray-900">{{ $details['name'] }}</span>
                                        @if(!empty($details['size']))
                                            <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md font-semibold">Size: {{ $details['size'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="inline-flex items-center justify-center space-x-1 border border-gray-200 rounded-lg p-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="quantity" value="{{ $details['quantity'] - 1 }}" class="w-7 h-7 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                        </button>
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-12 h-7 text-center border-none bg-transparent text-sm p-0 focus:ring-0" onchange="this.form.submit()">
                                        <button type="submit" name="quantity" value="{{ $details['quantity'] + 1 }}" class="w-7 h-7 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-6 text-right text-gray-600">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-right font-bold text-brand-600">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-center">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition p-2 hover:bg-red-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if(isset($apiShippingEnabled) && $apiShippingEnabled)
            <div class="p-6 border-t border-gray-100 bg-white grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        Calculate Shipping
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Province</label>
                            <select id="prov_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring-brand-500/20 px-3 py-2 text-sm">
                                <option value="">-- Select Province --</option>
                                @foreach($provinces ?? [] as $prov)
                                    <option value="{{ $prov['province_id'] }}">{{ $prov['province'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">City</label>
                            <select id="city_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring-brand-500/20 px-3 py-2 text-sm disabled:bg-gray-100" disabled>
                                <option value="">-- Select City --</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Weight (grams)</label>
                                <input type="number" id="weight" value="{{ $totalWeight }}" readonly class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Courier</label>
                                <select id="courier" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-500 focus:ring-brand-500/20 px-3 py-2 text-sm">
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                </select>
                            </div>
                        </div>
                        <button id="btn-check" type="button" class="w-full bg-brand-50 text-brand-700 font-bold py-2.5 rounded-lg hover:bg-brand-100 transition text-sm flex items-center justify-center">
                            <span id="btn-check-text">Check Cost</span>
                            <svg id="btn-check-spinner" class="animate-spin ml-2 h-4 w-4 text-brand-700 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-4">Shipping Options</h3>
                    <div id="shipping-results" class="space-y-3">
                        <div class="p-4 rounded-xl border border-dashed border-gray-200 text-center text-sm text-gray-500 bg-gray-50/50">
                            Please select destination and click Check Cost.
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between">
                <a href="{{ route('landing') }}" class="text-brand-600 font-medium hover:text-brand-800 transition mb-4 md:mb-0">
                    &larr; Continue Shopping
                </a>
                
                <form action="{{ route('checkout') }}" method="POST" id="checkout-form" class="w-full md:w-auto">
                    @csrf
                    <input type="hidden" name="shipping_cost" id="input_shipping_cost" value="0">
                    <input type="hidden" name="shipping_service" id="input_shipping_service" value="">
                    <input type="hidden" name="destination_city" id="input_destination_city" value="">
                    <input type="hidden" name="courier" id="input_courier" value="">
                    
                    <div class="flex flex-col md:flex-row items-center md:space-x-6 space-y-4 md:space-y-0 w-full">
                        <div class="text-right w-full md:w-auto">
                            <div class="flex justify-between md:justify-end md:space-x-4 text-sm text-gray-500 mb-1">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between md:justify-end md:space-x-4 text-sm text-gray-500 mb-2 {{ isset($apiShippingEnabled) && $apiShippingEnabled ? '' : 'hidden' }}">
                                <span>Shipping Cost</span>
                                <span id="display_shipping_cost">Rp 0</span>
                            </div>
                            <div class="flex justify-between md:justify-end md:space-x-4 border-t border-gray-200 pt-2">
                                <span class="font-medium text-gray-900 mt-1">Total Amount</span>
                                <span class="text-2xl font-bold text-gray-900" id="display_total_amount" data-subtotal="{{ $totalPrice }}">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <button type="submit" id="btn-checkout" class="w-full md:w-auto px-8 py-3.5 bg-brand-600 text-white font-bold rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Proceed to Checkout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Your cart is empty</h3>
            <p class="text-gray-500 mt-1 mb-6">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('landing') }}" class="inline-block bg-brand-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-lg shadow-brand-600/30 hover:bg-brand-700 transition">Start Shopping</a>
        </div>
    @endif

    @if(isset($apiShippingEnabled) && $apiShippingEnabled)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provSelect = document.getElementById('prov_id');
            const citySelect = document.getElementById('city_id');
            const btnCheck = document.getElementById('btn-check');
            const resultsDiv = document.getElementById('shipping-results');
            const BASE_URL = '{{ url("/cart/cities") }}';
            const ONGKIR_URL = '{{ url("/cart/ongkir") }}';

            provSelect.addEventListener('change', function() {
                const provId = this.value;
                citySelect.innerHTML = '<option value="">-- Select City --</option>';
                citySelect.disabled = true;

                if (provId) {
                    fetch(`${BASE_URL}/${provId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.length > 0) {
                                data.forEach(city => {
                                    const option = document.createElement('option');
                                    option.value = city.city_id;
                                    option.textContent = `${city.type} ${city.city_name}`;
                                    citySelect.appendChild(option);
                                });
                                citySelect.disabled = false;
                            }
                        });
                }
            });

            btnCheck.addEventListener('click', function() {
                const dest = citySelect.value;
                const weight = document.getElementById('weight').value;
                const courier = document.getElementById('courier').value;

                if (!dest) {
                    alert('Please select a destination city.');
                    return;
                }

                // Show loading
                document.getElementById('btn-check-text').classList.add('hidden');
                document.getElementById('btn-check-spinner').classList.remove('hidden');
                btnCheck.disabled = true;

                fetch(ONGKIR_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        destination: dest,
                        weight: weight,
                        courier: courier
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.text().then(text => { throw new Error(`HTTP ${res.status}: ${text.substring(0, 100)}`); });
                    }
                    return res.json();
                })
                .then(data => {
                    resultsDiv.innerHTML = '';
                    
                    if (data.error) {
                        resultsDiv.innerHTML = `<div class="p-4 rounded-xl bg-red-50 text-red-600 text-sm border border-red-100">${data.error}</div>`;
                        return;
                    }

                    if (data.length === 0) {
                        resultsDiv.innerHTML = `<div class="p-4 rounded-xl border border-dashed border-gray-200 text-center text-sm text-gray-500">No shipping options found for this courier.</div>`;
                        return;
                    }

                    data.forEach(cost => {
                        const costDetail = cost.cost[0];
                        const html = `
                            <label class="block p-4 rounded-xl border border-gray-200 cursor-pointer hover:border-brand-500 hover:shadow-sm transition-all duration-200 bg-white group radio-label">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <input type="radio" name="shipping_service_radio" value="${cost.service}" data-cost="${costDetail.value}" class="w-4 h-4 text-brand-600 border-gray-300 focus:ring-brand-500 shipping-radio">
                                        <div>
                                            <p class="font-bold text-gray-900 group-hover:text-brand-600 transition">${cost.service}</p>
                                            <p class="text-xs text-gray-500">${cost.description} (Etd: ${costDetail.etd} Hari)</p>
                                        </div>
                                    </div>
                                    <p class="font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(costDetail.value)}</p>
                                </div>
                            </label>
                        `;
                        resultsDiv.insertAdjacentHTML('beforeend', html);
                    });

                    // Add listeners to new radio buttons
                    document.querySelectorAll('.shipping-radio').forEach(radio => {
                        radio.addEventListener('change', function() {
                            // Reset styles
                            document.querySelectorAll('.radio-label').forEach(lbl => lbl.classList.remove('border-brand-500', 'bg-brand-50/30'));
                            this.closest('label').classList.add('border-brand-500', 'bg-brand-50/30');

                            const cost = parseInt(this.getAttribute('data-cost'));
                            const service = this.value;
                            
                            // Update hidden inputs
                            document.getElementById('input_shipping_cost').value = cost;
                            document.getElementById('input_shipping_service').value = service;
                            document.getElementById('input_destination_city').value = citySelect.options[citySelect.selectedIndex].text;
                            document.getElementById('input_courier').value = document.getElementById('courier').options[document.getElementById('courier').selectedIndex].text;

                            // Update displays
                            document.getElementById('display_shipping_cost').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(cost)}`;
                            
                            const subtotal = parseInt(document.getElementById('display_total_amount').getAttribute('data-subtotal'));
                            const total = subtotal + cost;
                            document.getElementById('display_total_amount').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
                        });
                    });

                })
                .catch(err => {
                    resultsDiv.innerHTML = `<div class="p-4 rounded-xl bg-red-50 text-red-600 text-sm border border-red-100">An error occurred while calculating shipping cost: ${err.message}</div>`;
                })
                .finally(() => {
                    document.getElementById('btn-check-text').classList.remove('hidden');
                    document.getElementById('btn-check-spinner').classList.add('hidden');
                    btnCheck.disabled = false;
                });
            });

            // Validate form before submit
            document.getElementById('checkout-form').addEventListener('submit', function(e) {
                if (document.getElementById('input_shipping_cost').value === '0') {
                    e.preventDefault();
                    alert('Please calculate and select a shipping option first.');
                    // Scroll to shipping section
                    document.getElementById('shipping-calculator').scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
    @endif
</x-layouts.dashboard>
