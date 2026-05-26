@extends('layouts.app')

@section('title', 'Register - Ship Spare Parts Store')

@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow p-8">
        <h1 class="text-center text-3xl font-bold mb-6">Create your account</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="/register" class="space-y-4" id="register-form">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold mb-1">Full Name</label>
                <input type="text" name="name" id="name" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('name') }}" />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold mb-1">Email Address</label>
                <input type="email" name="email" id="email" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('email') }}" />
            </div>

            <div>
                <label for="phone" class="block text-sm font-semibold mb-1">Phone Number</label>
                <input type="tel" name="phone" id="phone" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('phone') }}" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" name="password" id="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <p class="text-xs text-gray-600 mt-1">At least 8 characters</p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <label class="flex items-center text-sm">
                <input type="checkbox" required class="mr-2" />
                <span>I agree to the <a href="/terms" class="text-blue-600 hover:underline">Terms of Service</a></span>
            </label>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-semibold">
                Create Account
            </button>
        </form>

        <p class="text-center text-sm mt-4">
            Already have an account?
            <a href="/login" class="text-blue-600 hover:underline">Sign in</a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    // Check if user is already logged in (after successful registration)
    // If there's a pending cart item, add it after registration
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
                    showNotification('Account created! Product added to cart. Redirecting...', 'success', 'Welcome!');
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
