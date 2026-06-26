<x-layouts.dashboard title="My Orders">
    <div class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">My Orders</h2>
        <p class="text-sm text-gray-500 mt-1">View and track your previous orders.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ session('status') }}
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 pb-4 mb-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Order ID</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Date</p>
                            <p class="font-semibold text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Status</p>
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold uppercase tracking-wider mt-1">
                                {{ $order->status }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 font-medium">Total Amount</p>
                            <p class="font-bold text-brand-600 text-xl">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-xl text-sm text-gray-600">
                            <strong>Shipping Details:</strong> 
                            {{ $order->shipping_courier ? strtoupper($order->shipping_courier) : 'N/A' }} 
                            {{ $order->shipping_service ? '- ' . $order->shipping_service : '' }} 
                            (Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}) 
                            to {{ $order->destination_city ?: 'N/A' }}
                        </div>
                        
                        <div>
                            <h4 class="font-bold text-gray-900 mb-3 text-sm">Order Items</h4>
                            <div class="space-y-3">
                                @foreach($order->items as $item)
                                    <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2">
                                        <div class="flex items-center space-x-3">
                                            <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">{{ $item->quantity }}x</span>
                                            <span class="font-medium text-gray-800">{{ $item->product_name }}</span>
                                        </div>
                                        <span class="text-gray-600">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No Orders Yet</h3>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto">You haven't placed any orders. Start exploring our collection and find something you love!</p>
            <a href="{{ route('landing') }}" class="inline-block bg-brand-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition">
                Start Shopping
            </a>
        </div>
    @endif
</x-layouts.dashboard>
