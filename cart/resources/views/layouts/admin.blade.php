<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin Panel - Ship Spare Parts Store')</title>
    
    @php 
        $settings = \App\Models\AppSettings::getSettings();
    @endphp
    
    <!-- Favicon -->
    <link rel="icon" href="{{ $settings->getFaviconUrl() }}" type="image/x-icon">
    
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
    
    <script>
        // Check for dark mode preference on page load
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        /* Admin-wide dark surface overrides for legacy light utility classes */
        body.admin-dark .bg-white {
            background-color: #111827 !important;
            color: #e5e7eb;
        }

        body.admin-dark .bg-gray-50 {
            background-color: #1f2937 !important;
            color: #e5e7eb;
        }

        body.admin-dark .bg-gray-100 {
            background-color: #0b1220 !important;
            color: #e5e7eb;
        }

        body.admin-dark .border,
        body.admin-dark .border-b,
        body.admin-dark .border-t,
        body.admin-dark .border-l,
        body.admin-dark .border-r,
        body.admin-dark .divide-y > :not([hidden]) ~ :not([hidden]),
        body.admin-dark .divide-x > :not([hidden]) ~ :not([hidden]) {
            border-color: #374151 !important;
        }

        body.admin-dark .text-gray-900,
        body.admin-dark .text-gray-800,
        body.admin-dark .text-gray-700,
        body.admin-dark .text-gray-600,
        body.admin-dark .text-gray-500,
        body.admin-dark .text-gray-400 {
            color: #d1d5db !important;
        }

        body.admin-dark input,
        body.admin-dark select,
        body.admin-dark textarea {
            background-color: #111827 !important;
            color: #e5e7eb !important;
            border-color: #4b5563 !important;
        }

        body.admin-dark input::placeholder,
        body.admin-dark textarea::placeholder {
            color: #9ca3af !important;
        }

        body.admin-dark table thead {
            background-color: #1f2937 !important;
        }

        body.admin-dark tr:hover {
            background-color: #1f2937 !important;
        }
    </style>
    
    @yield('styles')
</head>
<body class="admin-dark bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white">
    <!-- Top Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-40 border-b dark:border-gray-700">
        <div class="px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <i class="fas fa-shield text-blue-600 text-xl"></i>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Admin Panel</h1>
            </div>
            
            <div class="flex items-center gap-6">
                <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400" title="Go to store">
                    <i class="fas fa-home"></i>
                </a>
                
                <!-- Dark Mode Toggle -->
                <button id="themeToggle" class="relative inline-flex items-center justify-center w-6 h-6 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 text-lg transition-colors" title="Toggle dark mode">
                    <i class="fas fa-moon absolute transition-opacity duration-200 dark:opacity-0 opacity-100"></i>
                    <i class="fas fa-sun absolute transition-opacity duration-200 opacity-0 dark:opacity-100"></i>
                </button>
                
                <div class="relative group">
                    <button class="flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                        <i class="fas fa-user-circle text-xl"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <div class="absolute right-0 top-full pt-1 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg hidden group-hover:block group-hover:z-50 z-40 border dark:border-gray-600">
                        <a href="/admin/profile" class="block px-4 py-2 text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
                        <form action="/logout" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-600 border-t dark:border-gray-600">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 dark:bg-gray-950 text-white min-h-[calc(100vh-64px)] border-r dark:border-gray-800">
            <nav class="p-6 space-y-2">
                <!-- Dashboard -->
                <a href="/admin" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 {{ Route::is('admin.dashboard') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Products -->
                <div class="space-y-2 group">
                    <button class="products-toggle w-full flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 cursor-pointer">
                        <i class="fas fa-box w-5"></i>
                        <span>Products</span>
                        <i class="fas fa-chevron-down ml-auto text-sm chevron transition-transform"></i>
                    </button>
                    <div class="products-menu hidden pl-8 space-y-1">
                        <a href="/admin/products" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-list w-4"></i>
                            <span>All Products</span>
                        </a>
                        <a href="/admin/products/create" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-plus w-4"></i>
                            <span>Add Product</span>
                        </a>
                        <a href="/admin/products/bulk-upload" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-upload w-4"></i>
                            <span>Bulk Upload</span>
                        </a>
                        <a href="/admin/categories" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-tag w-4"></i>
                            <span>Categories</span>
                        </a>
                        <a href="/admin/brands" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-trademark w-4"></i>
                            <span>Brands</span>
                        </a>
                    </div>
                </div>

                <!-- Orders -->
                <div class="space-y-2 group">
                    <button class="orders-toggle w-full flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 cursor-pointer">
                        <i class="fas fa-shopping-bag w-5"></i>
                        <span>Orders</span>
                        <i class="fas fa-chevron-down ml-auto text-sm chevron transition-transform"></i>
                    </button>
                    <div class="orders-menu hidden pl-8 space-y-1">
                        <a href="/admin/orders" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-list w-4"></i>
                            <span>All Orders</span>
                        </a>
                        <a href="/admin/orders?status=pending" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-clock w-4"></i>
                            <span>Pending</span>
                        </a>
                        <a href="/admin/orders?status=processing" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-cog w-4"></i>
                            <span>Processing</span>
                        </a>
                        <a href="/admin/orders?status=shipped" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-truck w-4"></i>
                            <span>Shipped</span>
                        </a>
                    </div>
                </div>

                <!-- Payments -->
                <div class="space-y-2 group">
                    <button class="payments-toggle w-full flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 cursor-pointer">
                        <i class="fas fa-credit-card w-5"></i>
                        <span>Payments</span>
                        <i class="fas fa-chevron-down ml-auto text-sm chevron transition-transform"></i>
                    </button>
                    <div class="payments-menu hidden pl-8 space-y-1">
                        <a href="/admin/payments" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-list w-4"></i>
                            <span>All Payments</span>
                        </a>
                        <a href="/admin/payments?status=pending" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-hourglass w-4"></i>
                            <span>Pending Verification</span>
                        </a>
                        <a href="/admin/payments?status=approved" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm">
                            <i class="fas fa-check w-4"></i>
                            <span>Approved</span>
                        </a>
                        <a href="/admin/payment-methods" class="flex items-center gap-2 px-4 py-2 rounded hover:bg-gray-800 text-sm border-t border-gray-700 mt-2 pt-2">
                            <i class="fas fa-cog w-4"></i>
                            <span>Payment Methods</span>
                        </a>
                    </div>
                </div>

                <!-- Inventory -->
                <a href="/admin/inventory" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 {{ Route::is('admin.inventory.index') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-warehouse w-5"></i>
                    <span>Inventory</span>
                </a>

                <!-- Customers -->
                <a href="/admin/customers" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 {{ Route::is('admin.customers.index') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span>Customers</span>
                </a>

                <!-- Chatbot FAQs -->
                <a href="/admin/faqs" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 {{ Route::is('admin.faqs.index') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-comments w-5"></i>
                    <span>Chatbot FAQs</span>
                </a>

                <!-- Pages (CMS) -->
                <a href="/admin/pages" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 {{ Route::is('admin.pages.*') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-file-alt w-5"></i>
                    <span>Pages</span>
                </a>

                <!-- Analytics -->
                <a href="/admin/analytics" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 {{ Route::is('admin.analytics.index') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Analytics</span>
                </a>

                <!-- Settings -->
                <a href="/admin/settings" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 {{ Route::is('admin.settings.index') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-gray-100 dark:bg-gray-900">
            <div class="p-8">
                <!-- Breadcrumb -->
                @isset($breadcrumbs)
                    <nav class="text-sm mb-6">
                        <a href="/admin" class="text-blue-600 dark:text-blue-400 hover:underline">Admin</a>
                        @foreach($breadcrumbs as $label => $url)
                            <span class="text-gray-600 dark:text-gray-400 mx-2">/</span>
                            @if($loop->last)
                                <span class="text-gray-600 dark:text-gray-400">{{ $label }}</span>
                            @else
                                <a href="{{ $url }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $label }}</a>
                            @endif
                        @endforeach
                    </nav>
                @endif

                <!-- Alerts -->
                @if($errors->any())
                    <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-100 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-100 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @vite(['resources/js/app.js'])
    <script>
        // Theme toggle
        const themeToggle = document.getElementById('themeToggle');
        themeToggle?.addEventListener('click', function() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark);
        });

        // Setup dropdown menus - show on hover
        function setupDropdownMenus() {
            const menuPairs = [
                { toggle: '.products-toggle', menu: '.products-menu' },
                { toggle: '.orders-toggle', menu: '.orders-menu' },
                { toggle: '.payments-toggle', menu: '.payments-menu' }
            ];

            menuPairs.forEach(({ toggle, menu }) => {
                const toggleBtn = document.querySelector(toggle);
                const menuDiv = document.querySelector(menu);
                const parent = toggleBtn?.parentElement;

                if (toggleBtn && menuDiv && parent) {
                    // Show menu on hover
                    parent.addEventListener('mouseenter', () => {
                        menuDiv.classList.remove('hidden');
                        const chevron = toggleBtn.querySelector('.chevron');
                        if (chevron) chevron.style.transform = 'rotate(180deg)';
                    });

                    // Hide menu when leaving
                    parent.addEventListener('mouseleave', () => {
                        menuDiv.classList.add('hidden');
                        const chevron = toggleBtn.querySelector('.chevron');
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    });

                    // Also allow click toggle for mobile
                    toggleBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        menuDiv.classList.toggle('hidden');
                    });
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', setupDropdownMenus);
    </script>

    <!-- Floating Chatbot Widget -->
    @include('components.chatbot-widget')

    @yield('scripts')
</body>
</html>
