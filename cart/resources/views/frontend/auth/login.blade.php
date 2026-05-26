@extends('layouts.app')

@section('title', 'Login - Ship Spare Parts Store')

@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow p-8">
        <h1 class="text-center text-3xl font-bold mb-6">Sign in to your account</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/login" class="space-y-4" id="login-form">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold mb-1">Email Address</label>
                <input type="email" name="email" id="email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('email') }}" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" name="password" id="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2" />
                <label for="remember" class="text-sm">Remember me</label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-semibold">
                Sign in
            </button>
        </form>

        <p class="text-center text-sm mt-4">
            Don't have an account?
            <a href="/register" class="text-blue-600 hover:underline">Sign up</a>
        </p>

        <div class="mt-4 pt-4 border-t">
            <p class="text-center text-xs text-gray-600">Admin Demo</p>
            <p class="text-center text-xs text-gray-600">Email: admin@example.com</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    // Check if user is already logged in (after successful login)
    // If there's a pending cart item, add it after login
    const pendingCartData = sessionStorage.getItem('pendingAddToCart');
    
    if (pendingCartData && {{ auth()->check() ? 'true' : 'false' }}) {
        // User is logged in and there's pending cart data
        const cartData = JSON.parse(pendingCartData);
        
        try {
            const response = await fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: cartData.product_id,
                    quantity: cartData.quantity
                })
            });

            if (response.ok) {
                // Clear the pending data
                sessionStorage.removeItem('pendingAddToCart');
                
                // Redirect to the appropriate page
                const data = await response.json();
                const returnUrl = cartData.return_url || '/cart';
                
                // If return_url is product details page, show success and stay there
                if (cartData.return_url && cartData.return_url.includes('/products/')) {
                    showNotification('Product added to cart!', 'success', 'Success');
                    setTimeout(() => {
                        window.location.href = cartData.return_url;
                    }, 1000);
                } else {
                    // Otherwise go to cart page
                    showNotification('Product added to cart! Redirecting...', 'success', 'Success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/cart';
                    }, 1000);
                }
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            showNotification('Error adding product to cart', 'error', 'Error');
        }
    }
});
</script>
@endsection
