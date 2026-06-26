<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Order::with('items')->where('user_id', auth()->id())->latest()->get();
        return view('orders.index', compact('orders'));
    }
}
