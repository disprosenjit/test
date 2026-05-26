@extends('layouts.app')

@section('title', 'Shopping Cart - Ship Spare Parts Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            @if($cartItems && count($cartItems) > 0)
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white p-4 rounded-lg shadow flex gap-4">
                            <!-- Product Image -->
                            <a href="/products/{{ $item['product']['id'] }}" class="flex-shrink-0">
                                <img 
                                    src="{{ (is_array($item['product']['images']) ? $item['product']['images'] : json_decode($item['product']['images'], true) ?? [])[0] ?? 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=80&q=80' }}" 
                                    alt="{{ $item['product']['name'] }}"
                                    class="w-20 h-20 object-cover rounded"
                                >
                            </a>

                            <!-- Product Info -->
                            <div class="flex-1">
                                <a href="/products/{{ $item['product']['id'] }}" class="font-semibold hover:text-blue-600">
                                    {{ $item['product']['name'] }}
                                </a>
                                <p class="text-sm text-gray-600">SKU: {{ $item['product']['sku'] }}</p>
                                <p class="text-lg font-bold text-blue-600">₹{{ number_format($item['product']['price'], 2) }}</p>
                            </div>

                            <!-- Quantity & Price -->
                            <div class="text-right">
                                <div class="flex items-center gap-2 mb-2 justify-end">
                                    <button onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})" class="px-2 py-1 border rounded hover:bg-gray-100">−</button>
                                    <span class="px-3 py-1">{{ $item['quantity'] }}</span>
                                    <button onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})" class="px-2 py-1 border rounded hover:bg-gray-100">+</button>
                                </div>
                                <p class="font-bold mb-2">₹{{ number_format($item['product']['price'] * $item['quantity'], 2) }}</p>
                                <button onclick="removeFromCart({{ $item['id'] }})" class="text-red-600 hover:text-red-700 text-sm">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Continue Shopping -->
                <div class="mt-6">
                    <a href="/products" class="text-blue-600 hover:underline">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Continue Shopping
                    </a>
                </div>
            @else
                <div class="bg-white p-12 rounded-lg text-center">
                    <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 mb-4">Your cart is empty</p>
                    <a href="/products" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>

        <!-- Cart Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow sticky top-20">
                <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                
                @if($cartItems && count($cartItems) > 0)
                    <div class="space-y-3 mb-4">
                        @php
                            $subtotal = 0;
                            foreach($cartItems as $item) {
                                $subtotal += $item['product']['price'] * $item['quantity'];
                            }
                            $tax = $subtotal * 0.18;
                            $total = $subtotal + $tax;
                        @endphp

                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (18%)</span>
                            <span>₹{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span>{{ $subtotal >= 5000 ? 'FREE' : '₹500' }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span class="text-blue-600">₹{{ number_format($total + ($subtotal < 5000 ? 500 : 0), 2) }}</span>
                        </div>
                    </div>

                    <a href="/checkout" class="block w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 text-center font-semibold mb-2">
                        Proceed to Checkout
                    </a>
                    <button type="button" onclick="clearCart()" class="w-full bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300">
                        Clear Cart
                    </button>

                    <!-- Promo Code -->
                    <div class="mt-4 pt-4 border-t">
                        <input type="text" placeholder="Promo code" class="w-full px-3 py-2 border rounded mb-2">
                        <button class="w-full bg-gray-100 text-gray-800 py-2 rounded hover:bg-gray-200 text-sm">
                            Apply Code
                        </button>
                    </div>
                @endif

                <!-- Features -->
                <div class="mt-6 pt-6 border-t space-y-3 text-sm">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-check text-green-600"></i>
                        Free shipping above ₹5,000
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-check text-green-600"></i>
                        Secure payment
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-check text-green-600"></i>
                        Easy returns
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function updateQuantity(itemId, quantity) {
    if (quantity < 1) {
        await removeFromCart(itemId);
        return;
    }

    try {
        const response = await fetch(`/api/cart/update/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity: quantity
            })
        });

        if (response.ok) {
            location.reload();
        } else {
            showNotification('Error updating cart', 'error', 'Error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error updating cart', 'error', 'Error');
    }
}

async function removeFromCart(itemId) {
    try {
        const response = await fetch(`/api/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            location.reload();
        } else {
            showNotification('Error removing from cart', 'error', 'Error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error removing from cart', 'error', 'Error');
    }
}

async function clearCart() {
    if (!confirm('Are you sure you want to clear your cart?')) return;

    try {
        const response = await fetch('/api/cart/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            location.reload();
        } else {
            showNotification('Error clearing cart', 'error', 'Error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error clearing cart', 'error', 'Error');
    }
}
</script>
@endsection
