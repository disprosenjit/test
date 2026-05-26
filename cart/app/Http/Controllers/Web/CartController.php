<?php

namespace App\Http\Controllers\Web;

use App\Models\Commerce\Cart;
use App\Models\Commerce\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * Display shopping cart
     */
    public function index(Request $request)
    {
        $cartItems = [];

        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())
                ->active()
                ->latest()
                ->first();
            if ($cart) {
                $cartItems = $cart->items()
                    ->with('product')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product' => $item->product->toArray(),
                            'quantity' => $item->quantity,
                        ];
                    })
                    ->toArray();
            }
        } else {
            // Get from session for guest users
            $cartItems = session('cart', []);
        }

        return view('frontend.cart.index', compact('cartItems'));
    }
}
