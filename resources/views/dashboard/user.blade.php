<x-layouts.dashboard title="My Dashboard">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="text-sm text-gray-500 mt-1 flex items-center space-x-2">
                <span>Member since {{ Auth::user()->created_at->format('M Y') }}</span>
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ url('/') }}" class="px-5 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/25">
                Browse Shop
            </a>
            <a href="{{ route('cart.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition">
                View Cart ({{ $cartCount }})
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Orders</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalOrders }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center shadow-lg shadow-brand-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Pending Orders</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $pendingOrders }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 card-hover shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Items in Cart</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $cartCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
            <a href="{{ route('orders.index') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3 px-2 font-medium">Order ID</th>
                        <th class="py-3 px-2 font-medium">Date</th>
                        <th class="py-3 px-2 font-medium text-right">Total</th>
                        <th class="py-3 px-2 font-medium text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3.5 px-2 font-medium text-brand-600">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3.5 px-2 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="py-3.5 px-2 text-right font-medium text-gray-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-2 text-center">
                            @if($order->status == 'pending')
                                <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700">Pending</span>
                            @elseif($order->status == 'paid')
                                <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full bg-emerald-100 text-emerald-700">Paid</span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full bg-gray-100 text-gray-700">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">
                            You don't have any orders yet. <a href="{{ url('/') }}" class="text-brand-600 hover:underline">Start shopping</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>