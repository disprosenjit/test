<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\CartService;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->check() ? auth()->user() : null;
        $sessionId = $request->session()->getId() ?? \Illuminate\Support\Str::uuid();
        $cartService = new CartService($user, $sessionId);

        return response()->json($cartService->getSummary());
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        // Check if user is not authenticated
        if (!auth()->check()) {
            return response()->json([
                'error' => 'Unauthenticated',
                'redirect' => route('login'),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ], 401);
        }

        try {
            $user = auth()->user();
            $sessionId = $request->session()->getId() ?? \Illuminate\Support\Str::uuid();
            $cartService = new CartService($user, $sessionId);
            $cartService->addProduct($request->product_id, $request->quantity);

            return response()->json([
                'message' => 'Product added to cart',
                'cart' => $cartService->getSummary(),
                'redirect' => route('cart.index'), // Redirect to cart page for logged-in users
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        try {
            $user = auth()->check() ? auth()->user() : null;
            $sessionId = $request->session()->getId() ?? \Illuminate\Support\Str::uuid();
            $cartService = new CartService($user, $sessionId);
            
            $cartService->updateItem($itemId, $request->quantity);

            return response()->json($cartService->getSummary());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function remove(Request $request, int $itemId)
    {
        try {
            $user = auth()->check() ? auth()->user() : null;
            $sessionId = $request->session()->getId() ?? \Illuminate\Support\Str::uuid();
            $cartService = new CartService($user, $sessionId);
            
            // Verify the item exists in the cart before removing
            $cartItem = $cartService->getCart()->items()->find($itemId);
            if (!$cartItem) {
                return response()->json(['error' => 'Cart item not found'], 404);
            }
            
            $cartService->removeItem($itemId);

            return response()->json($cartService->getSummary());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function clear(Request $request)
    {
        try {
            $user = auth()->check() ? auth()->user() : null;
            $sessionId = $request->session()->getId() ?? \Illuminate\Support\Str::uuid();
            $cartService = new CartService($user, $sessionId);
            $cartService->clear();

            return response()->json(['message' => 'Cart cleared']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
