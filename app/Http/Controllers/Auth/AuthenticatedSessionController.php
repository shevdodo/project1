<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        // Restore or merge Cart
        $sessionCart = session()->get('cart', []);
        $dbCart = [];
        if ($user->cart_data) {
            $dbCart = json_decode($user->cart_data, true) ?? [];
        }

        // Merge carts (session overrides db if same item exists)
        $mergedCart = array_merge($dbCart, $sessionCart);
        session()->put('cart', $mergedCart);
        
        // Save merged cart back to db
        $user->cart_data = json_encode($mergedCart);
        $user->save();

        if ($user->isSuperuser()) {
            return redirect()->intended(route('superuser.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
