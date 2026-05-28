<?php

namespace App\Services;

use App\Models\Commerce\Cart;
use App\Models\User\User;
use App\Models\Commerce\Product;

class CartService
{
    protected Cart $cart;
    protected ?User $user;
    protected ?string $sessionId;

    public function __construct(?User $user = null, ?string $sessionId = null)
    {
        $this->user = $user;
        $this->sessionId = $sessionId;
        $this->initializeCart();
    }

    /**
     * Initialize or get existing cart
     */
    protected function initializeCart(): void
    {
        if ($this->user) {
            $this->cart = $this->user->getActiveCart() ?? Cart::create([
                'user_id' => $this->user->id,
                'session_id' => $this->sessionId,
                'expires_at' => now()->addDays(7),
            ]);
        } else {
            // Guest cart via session
            $this->cart = Cart::where('session_id', $this->sessionId)
                ->active()
                ->first() ?? Cart::create([
                    'session_id' => $this->sessionId,
                    'expires_at' => now()->addDays(7),
                ]);
        }
    }

    /**
     * Add product to cart
     */
    public function addProduct(int $productId, int $quantity = 1): Cart
    {
        $product = Product::with('inventory')->findOrFail($productId);
        $availableStock = $this->getAvailableStock($product);

        // Check stock
        if ($availableStock < $quantity) {
            throw new \Exception("Insufficient stock. Available: {$availableStock}");
        }

        $this->cart->addItem($productId, $quantity, $product->price);

        return $this->cart;
    }

    /**
     * Update product quantity
     */
    public function updateProductQuantity(int $productId, int $quantity): Cart
    {
        if ($quantity <= 0) {
            return $this->removeProduct($productId);
        }

        $cartItem = $this->cart->items()->where('product_id', $productId)->firstOrFail();
        $product = $cartItem->product()->with('inventory')->firstOrFail();
        $availableStock = $this->getAvailableStock($product);

        if ($availableStock < $quantity) {
            throw new \Exception("Insufficient stock. Available: {$availableStock}");
        }

        return $this->cart->updateItem($cartItem->id, $quantity);
    }

    /**
     * Remove product from cart
     */
    public function removeProduct(int $productId): Cart
    {
        $cartItem = $this->cart->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            $this->cart->removeItem($cartItem->id);
        }

        return $this->cart;
    }

    /**
     * Remove item from cart by item ID
     */
    public function removeItem(int $itemId): Cart
    {
        $this->cart->removeItem($itemId);
        return $this->cart;
    }

    /**
     * Update item quantity in cart by item ID
     */
    public function updateItem(int $itemId, int $quantity): Cart
    {
        $this->cart->updateItem($itemId, $quantity);
        return $this->cart;
    }

    /**
     * Get cart contents with totals
     */
    public function getCart(): Cart
    {
        return $this->cart->load('items.product');
    }

    /**
     * Get cart items count
     */
    public function getItemCount(): int
    {
        return $this->cart->items()->sum('quantity');
    }

    /**
     * Clear cart
     */
    public function clear(): Cart
    {
        return $this->cart->clear();
    }

    /**
     * Merge guest cart to user cart (after login)
     */
    public function mergeGuestCart(string $guestSessionId): Cart
    {
        $guestCart = Cart::where('session_id', $guestSessionId)
            ->active()
            ->first();

        if ($guestCart) {
            foreach ($guestCart->items as $item) {
                try {
                    $this->addProduct($item->product_id, $item->quantity);
                } catch (\Exception $e) {
                    // Skip items that can't be added
                    continue;
                }
            }

            // Clear guest cart
            $guestCart->clear();
            $guestCart->delete();
        }

        return $this->cart;
    }

    /**
     * Get cart summary
     */
    public function getSummary()
    {
        // Refresh cart data from database
        $this->cart->refresh();
        
        return [
            'item_count' => $this->getItemCount(),
            'subtotal' => $this->cart->subtotal ?? 0,
            'tax' => $this->cart->tax ?? 0,
            'total' => $this->cart->total ?? 0,
            'items' => $this->cart->items()->with('product')->get(),
        ];
    }

    /**
     * Validate cart before checkout
     */
    public function validate(): array
    {
        $errors = [];

        if ($this->cart->items()->count() === 0) {
            $errors[] = 'Cart is empty';
        }

        foreach ($this->cart->items as $item) {
            $availableStock = $this->getAvailableStock($item->product);

            if ($availableStock < $item->quantity) {
                $errors[] = "Insufficient stock for {$item->product->name}. Available: {$availableStock}";
            }

            if (!$item->product->is_active) {
                $errors[] = "{$item->product->name} is no longer available";
            }
        }

        return $errors;
    }

    /**
     * Resolve available stock using inventory first, then fallback to product stock_qty.
     */
    protected function getAvailableStock(Product $product): int
    {
        if ($product->isDownloadable()) {
            return PHP_INT_MAX;
        }

        if ($product->relationLoaded('inventory') && $product->inventory) {
            return (int) ($product->inventory->available_qty ?? 0);
        }

        if ($product->inventory()->exists()) {
            $inventory = $product->inventory()->first();
            return (int) ($inventory->available_qty ?? 0);
        }

        return (int) ($product->stock_qty ?? 0);
    }
}
