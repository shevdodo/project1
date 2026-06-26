<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $orders = \App\Models\Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $totalOrders = \App\Models\Order::where('user_id', $user->id)->count();
        $pendingOrders = \App\Models\Order::where('user_id', $user->id)->where('status', 'pending')->count();
        
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('dashboard.user', compact('orders', 'totalOrders', 'pendingOrders', 'cartCount'));
    }
}
