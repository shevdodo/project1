<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $apiShippingEnabled = \App\Models\Setting::where('key', 'api_shipping_enabled')->value('value') == '1';
        $apiKey = \App\Models\Setting::where('key', 'api_shipping_key')->value('value');
        $provinces = [];
        
        if ($apiShippingEnabled && !empty($apiKey)) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(3)->withoutVerifying()->withHeaders([
                    'key' => $apiKey
                ])->get('https://api.rajaongkir.com/starter/province');
                
                if ($response->successful()) {
                    $provinces = $response->json()['rajaongkir']['results'] ?? [];
                } else {
                    throw new \Exception('API Error: ' . $response->body());
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('RajaOngkir Exception: ' . $e->getMessage());
                // Fallback Mock Data for Development/Sandbox if API fails
                $provinces = [
                    ['province_id' => '9', 'province' => 'Jawa Barat'],
                    ['province_id' => '10', 'province' => 'Jawa Tengah'],
                    ['province_id' => '11', 'province' => 'Jawa Timur'],
                    ['province_id' => '6', 'province' => 'DKI Jakarta'],
                ];
            }
        }

        return view('cart.index', compact('cart', 'apiShippingEnabled', 'provinces'));
    }

    private function syncCart($cart)
    {
        session()->put('cart', $cart);
        
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $user->cart_data = json_encode($cart);
            $user->save();
        }
    }

    public function add(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, produk ini sedang habis stok.');
        }

        $cart = session()->get('cart', []);
        $size = $request->input('size');
        $cartKey = $size ? $id . '-' . $size : $id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
                'size' => $size
            ];
        }

        $this->syncCart($cart);
        return redirect()->route('cart.index')->with('status', 'Product added to cart successfully!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            $quantity = (int) $request->input('quantity');
            if ($quantity > 0) {
                $cart[$id]['quantity'] = $quantity;
            } else {
                unset($cart[$id]);
            }
            $this->syncCart($cart);
        }
        return redirect()->route('cart.index')->with('status', 'Cart updated successfully.');
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            $this->syncCart($cart);
        }
        return redirect()->route('cart.index')->with('status', 'Product removed from cart.');
    }

    public function getCities($provinceId)
    {
        $apiKey = \App\Models\Setting::where('key', 'api_shipping_key')->value('value');
        if (empty($apiKey)) return response()->json([]);

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)->withoutVerifying()->withHeaders([
                'key' => $apiKey
            ])->get('https://api.rajaongkir.com/starter/city', [
                'province' => $provinceId
            ]);
            
            if ($response->successful()) {
                return response()->json($response->json()['rajaongkir']['results'] ?? []);
            }
            throw new \Exception('API Error: ' . $response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('RajaOngkir City Exception: ' . $e->getMessage());
            // Fallback Mock Data
            $mockCities = [
                ['city_id' => '1', 'type' => 'Kota', 'city_name' => 'Bandung'],
                ['city_id' => '2', 'type' => 'Kota', 'city_name' => 'Surakarta (Solo)'],
                ['city_id' => '3', 'type' => 'Kota', 'city_name' => 'Surabaya'],
                ['city_id' => '4', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat'],
            ];
            return response()->json($mockCities);
        }
    }

    public function checkOngkir(Request $request)
    {
        $apiKey = \App\Models\Setting::where('key', 'api_shipping_key')->value('value');
        if (empty($apiKey)) return response()->json(['error' => 'API Key not set'], 400);

        $request->validate([
            'destination' => 'required|numeric',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|string|in:jne,pos,tiki'
        ]);

        try {
            // Usually the origin is the shop's location. Let's hardcode Solo/Surakarta for now or get from settings.
            // ID for Surakarta is 445 in RajaOngkir starter.
            $originId = \App\Models\Setting::where('key', 'store_city_id')->value('value') ?: 445; 

            $response = \Illuminate\Support\Facades\Http::timeout(4)->withoutVerifying()->withHeaders([
                'key' => $apiKey
            ])->post('https://api.rajaongkir.com/starter/cost', [
                'origin' => $originId,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier
            ]);
            
            if ($response->successful()) {
                return response()->json($response->json()['rajaongkir']['results'][0]['costs'] ?? []);
            }
            throw new \Exception('API Error: ' . $response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('RajaOngkir Cost Exception: ' . $e->getMessage());
            
            // Fallback Mock Data for demo/sandbox
            $courier = strtoupper($request->courier);
            $mockCosts = [
                [
                    "service" => "REG",
                    "description" => "Layanan Reguler",
                    "cost" => [
                        [
                            "value" => rand(15000, 25000),
                            "etd" => "2-3",
                            "note" => ""
                        ]
                    ]
                ],
                [
                    "service" => "YES",
                    "description" => "Yakin Esok Sampai",
                    "cost" => [
                        [
                            "value" => rand(30000, 45000),
                            "etd" => "1-1",
                            "note" => ""
                        ]
                    ]
                ]
            ];
            return response()->json($mockCosts);
        }
    }
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_service' => 'nullable|string',
            'destination_city' => 'nullable|string',
            'courier' => 'nullable|string',
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingCost = $request->input('shipping_cost', 0);
        $totalAmount = $subtotal + $shippingCost;

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totalAmount,
            'shipping_courier' => $request->courier,
            'shipping_service' => $request->shipping_service,
            'destination_city' => $request->destination_city,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($cart as $id => $item) {
            $productId = isset($item['product_id']) ? $item['product_id'] : (int)$id;
            $productName = $item['name'];
            if (!empty($item['size'])) {
                $productName .= ' (Size: ' . $item['size'] . ')';
            }

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $productName,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ]);

            // Decrement stock
            $product = \App\Models\Product::find($productId);
            if ($product) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        // Clear cart
        $this->syncCart([]);

        return redirect()->route('orders.index')->with('status', 'Order created successfully. Please complete your payment.');
    }
}
