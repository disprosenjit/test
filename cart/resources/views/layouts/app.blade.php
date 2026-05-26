<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Ship Spare Parts Store')</title>
    
    @php 
        $settings = \App\Models\AppSettings::getSettings();
    @endphp
    
    <!-- Favicon -->
    <link rel="icon" href="{{ $settings->getFaviconUrl() }}" type="image/x-icon">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Theme Colors -->
    <style>
        :root {
            --primary-color: {{ $settings->primary_color ?? '#2563eb' }};
            --secondary-color: {{ $settings->secondary_color ?? '#1e40af' }};
            --accent-color: {{ $settings->accent_color ?? '#3b82f6' }};
            --dark-bg: {{ $settings->dark_mode_bg ?? '#1f2937' }};
            --dark-text: {{ $settings->dark_mode_text ?? '#f3f4f6' }};
            --dark-accent: {{ $settings->dark_mode_accent ?? '#60a5fa' }};
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        @if($settings->logo_path)
                            <img src="{{ $settings->getLogoUrl() }}" alt="Logo" class="h-10">
                        @else
                            <i class="fas fa-ship text-blue-600 text-2xl"></i>
                        @endif
                        <span class="font-bold text-xl text-gray-800">{{ $settings->store_name ?? 'Ship Parts Store' }}</span>
                    </a>
                </div>

                <!-- Search Bar -->
                @if(!in_array(Route::currentRouteName(), ['login', 'register']))
                    <div class="flex-1 max-w-md mx-8 my-auto">
                        <form action="/products" method="GET" class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search part number..."
                                class="w-full px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ request('search') }}"
                            >
                            <button type="submit" class="absolute right-3 top-2.5 text-gray-600 hover:text-blue-600">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex-1"></div>
                @endif

                <!-- Right Icons -->
                <div class="flex items-center gap-6">
                    <!-- Cart Icon -->
                    @if(!in_array(Route::currentRouteName(), ['login', 'register']))
                        <a href="/cart" class="relative text-gray-700 hover:text-blue-600">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </a>
                    @endif

                    <!-- User Menu -->
                    <div class="relative group">
                        @auth
                            <button class="flex items-center gap-2 text-gray-700 hover:text-blue-600">
                                <i class="fas fa-user-circle text-xl"></i>
                                <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            </button>
                            <div class="absolute right-0 top-full pt-1 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block group-hover:z-50 z-40">
                                <a href="/profile" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                                <a href="/orders" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
                                @if(Auth::user()->role === 'admin')
                                    <a href="/admin" class="block px-4 py-2 hover:bg-gray-100 border-t">Admin Panel</a>
                                @endif
                                <form action="/logout" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 border-t">Logout</button>
                                </form>
                            </div>
                        @else
                            <a href="/login" class="text-gray-700 hover:text-blue-600">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @if($errors->any())
            <div class="max-w-7xl mx-auto mt-4 px-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="max-w-7xl mx-auto mt-4 px-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Floating Chatbot Widget -->
    @if(!in_array(Route::currentRouteName(), ['login', 'register']))
        @include('components.chatbot-widget')
    @endif

    <!-- Notification Toast -->
    @include('components.notification-toast')

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="font-bold mb-4">Ship Spare Parts</h3>
                    <p class="text-gray-400">Your trusted provider of maritime spare parts and equipment.</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/products" class="hover:text-white">Products</a></li>
                        @php
                            $quickPages = \App\Models\Content\Page::where('is_active', true)->limit(3)->get();
                        @endphp
                        @foreach($quickPages as $page)
                            <li><a href="/pages/{{ $page->slug }}" class="hover:text-white">{{ $page->title }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="font-bold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/faqs" class="hover:text-white">FAQs</a></li>
                        <li><a href="/help" class="hover:text-white">Help Center</a></li>
                        <li><a href="/shipping" class="hover:text-white">Shipping Info</a></li>
                        <li><a href="/returns" class="hover:text-white">Returns</a></li>
                        @php
                            $supportPages = \App\Models\Content\Page::where('is_active', true)->skip(3)->limit(1)->get();
                        @endphp
                        @foreach($supportPages as $page)
                            <li><a href="/pages/{{ $page->slug }}" class="hover:text-white">{{ $page->title }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="font-bold mb-4">Contact</h3>
                    <p class="text-gray-400">{{ \App\Models\AppSettings::getSettings()->footer_contact_description ?? 'Your trusted provider of maritime spare parts and equipment.' }}</p>
                    <p class="text-gray-400">Email: support@shipparts.com</p>
                    <p class="text-gray-400">Phone: +91-1234-567-890</p>
                    <div class="flex gap-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 Ship Spare Parts Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        function updateCartCount() {
            fetch('/api/cart', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const count = data.data ? data.data.items.reduce((sum, item) => sum + item.quantity, 0) : 0;
                document.getElementById('cart-count').textContent = count;
            })
            .catch(error => console.error('Error fetching cart:', error));
        }

        // Add to cart function
        async function addToCart(productId) {
            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // User is logged in - show success and go to cart page
                    showNotification('Product added to cart successfully!', 'success', 'Success');
                    updateCartCount();
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else if (response.status === 401) {
                    // User not logged in - store product info and redirect to login
                    const productUrl = '/products/' + productId;
                    sessionStorage.setItem('pendingAddToCart', JSON.stringify({
                        product_id: productId,
                        quantity: 1,
                        return_url: productUrl
                    }));
                    showNotification('Redirecting to login...', 'info', 'Please Login');
                    setTimeout(() => {
                        window.location.href = data.redirect + '?return_to=' + encodeURIComponent(productUrl);
                    }, 1000);
                } else {
                    showNotification(data.message || 'Error adding to cart', 'error', 'Error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Failed to add item to cart. Please try again.', 'error', 'Error');
            }
        }

        // Event listener for cart updates
        window.addEventListener('cart-updated', updateCartCount);
    </script>

    @yield('scripts')
</body>
</html>
