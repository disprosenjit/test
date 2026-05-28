<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>

    @stack('head')
</head>
<body class="bg-white text-slate-900 antialiased">

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center h-16 gap-6">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-3 flex-shrink-0">
                    <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-building text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-bold text-slate-900 whitespace-nowrap">{{ config('app.name') }}</span>
                </a>

                {{-- Desktop Links --}}
                <div class="hidden lg:flex flex-1 items-center justify-center gap-16">
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-2 px-1 text-sm font-medium whitespace-nowrap transition-colors {{ request()->routeIs('home') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        <i class="fa-solid fa-house text-xs"></i>Home
                    </a>
                    <a href="{{ route('properties.index') }}"
                       class="flex items-center gap-2 px-1 text-sm font-medium whitespace-nowrap transition-colors {{ request()->routeIs('properties.*') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        <i class="fa-solid fa-building text-xs"></i>Properties
                    </a>
                    <a href="{{ route('map') }}"
                       class="flex items-center gap-2 px-1 text-sm font-medium whitespace-nowrap transition-colors {{ request()->routeIs('map') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        <i class="fa-solid fa-map-location-dot text-xs"></i>Map
                    </a>
                    <a href="{{ route('about') }}"
                       class="flex items-center gap-2 px-1 text-sm font-medium whitespace-nowrap transition-colors {{ request()->routeIs('about') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        <i class="fa-solid fa-circle-info text-xs"></i>About
                    </a>
                    <a href="{{ route('contact.create') }}"
                       class="flex items-center gap-2 px-1 text-sm font-medium whitespace-nowrap transition-colors {{ request()->routeIs('contact.*') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        <i class="fa-solid fa-envelope text-xs"></i>Contact
                    </a>
                </div>

                {{-- CTA + Mobile Toggle --}}
                <div class="flex items-center gap-3 flex-shrink-0 ml-auto lg:ml-0">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="hidden lg:inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors whitespace-nowrap">
                                Dashboard
                            </a>
                        @else
                            {{-- Desktop user dropdown --}}
                            <div class="hidden lg:block relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
                                <button @click="userMenuOpen = !userMenuOpen"
                                        class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                    <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <span class="max-w-28 truncate">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="userMenuOpen ? 'rotate-180' : ''"></i>
                                </button>

                                <div x-show="userMenuOpen"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     x-cloak
                                     class="absolute right-0 mt-2 w-52 bg-white rounded-xl border border-slate-200 shadow-lg py-1 z-50">
                                    <div class="px-4 py-2.5 border-b border-slate-100">
                                        <p class="text-xs text-slate-400">Signed in as</p>
                                        <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('bookings.index') }}"
                                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                        <i class="fas fa-calendar-check w-4 text-center text-slate-400"></i>My Bookings
                                    </a>
                                    <a href="{{ route('profile.edit') }}"
                                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                        <i class="fas fa-user w-4 text-center text-slate-400"></i>My Profile
                                    </a>
                                    <div class="border-t border-slate-100 mt-1 pt-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                                <i class="fas fa-sign-out-alt w-4 text-center"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="hidden lg:flex items-center gap-2">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors whitespace-nowrap">
                                Log In
                            </a>
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors whitespace-nowrap">
                                Sign Up
                            </a>
                        </div>
                    @endauth

                    {{-- Mobile Menu Button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="lg:hidden p-2 text-slate-600 hover:text-slate-900 transition-colors"
                            aria-label="Toggle menu">
                        <i x-show="!mobileMenuOpen" class="fa-solid fa-bars text-xl"></i>
                        <i x-show="mobileMenuOpen" class="fa-solid fa-xmark text-xl" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-cloak
             class="lg:hidden border-t border-slate-200 bg-white">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ route('home') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-house w-4 text-center"></i>Home
                </a>
                <a href="{{ route('properties.index') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('properties.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-building w-4 text-center"></i>Properties
                </a>
                <a href="{{ route('map') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('map') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-map-location-dot w-4 text-center"></i>Map
                </a>
                <a href="{{ route('about') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('about') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-circle-info w-4 text-center"></i>About
                </a>
                <a href="{{ route('contact.create') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('contact.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-envelope w-4 text-center"></i>Contact
                </a>
                <div class="pt-2">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="block w-full text-center px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <div class="border-t border-slate-100 pt-2 space-y-1">
                                <p class="px-4 py-1 text-xs text-slate-400 font-medium uppercase tracking-wider">{{ auth()->user()->name }}</p>
                                <a href="{{ route('bookings.index') }}"
                                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('bookings.index') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                                    <i class="fas fa-calendar-check w-4 text-center"></i>My Bookings
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                                    <i class="fas fa-user w-4 text-center"></i>My Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-2 w-full px-4 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt w-4 text-center"></i>Logout
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="flex flex-col gap-2 pt-2 border-t border-slate-100">
                            <a href="{{ route('login') }}"
                               class="block w-full text-center px-5 py-2.5 border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
                                Log In
                            </a>
                            <a href="{{ route('register') }}"
                               class="block w-full text-center px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                                Sign Up
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Brand --}}
                <div>
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 mb-6">
                        <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-building text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-white">{{ config('app.name') }}</span>
                    </a>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Your trusted partner in finding the perfect property. We bring expertise, integrity, and dedication to every transaction.
                    </p>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-colors" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-colors" aria-label="Twitter">
                            <i class="fa-brands fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-colors" aria-label="Instagram">
                            <i class="fa-brands fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-colors" aria-label="LinkedIn">
                            <i class="fa-brands fa-linkedin-in text-sm"></i>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-slate-300 hover:text-white text-sm transition-colors">Home</a></li>
                        <li><a href="{{ route('properties.index') }}" class="text-slate-300 hover:text-white text-sm transition-colors">Properties</a></li>
                        <li><a href="{{ route('about') }}" class="text-slate-300 hover:text-white text-sm transition-colors">About Us</a></li>
                        <li><a href="{{ route('contact.create') }}" class="text-slate-300 hover:text-white text-sm transition-colors">Contact</a></li>
                    </ul>
                </div>

                {{-- Property Types --}}
                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">Property Types</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('properties.index') }}?type=house" class="text-slate-300 hover:text-white text-sm transition-colors">Houses</a></li>
                        <li><a href="{{ route('properties.index') }}?type=apartment" class="text-slate-300 hover:text-white text-sm transition-colors">Apartments</a></li>
                        <li><a href="{{ route('properties.index') }}?type=commercial" class="text-slate-300 hover:text-white text-sm transition-colors">Commercial</a></li>
                        <li><a href="{{ route('properties.index') }}?type=land" class="text-slate-300 hover:text-white text-sm transition-colors">Land</a></li>
                        <li><a href="{{ route('properties.index') }}?type=villa" class="text-slate-300 hover:text-white text-sm transition-colors">Villas</a></li>
                    </ul>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">Contact Info</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <i class="fa-solid fa-location-dot text-blue-400 mt-1"></i>
                            <span class="text-slate-300 text-sm">123 Business Avenue, Suite 100, New York, NY 10001</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-phone text-blue-400"></i>
                            <a href="tel:+15551234567" class="text-slate-300 hover:text-white text-sm transition-colors">+1 (555) 123-4567</a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-envelope text-blue-400"></i>
                            <a href="mailto:info@realestatepro.com" class="text-slate-300 hover:text-white text-sm transition-colors">info@realestatepro.com</a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fa-solid fa-clock text-blue-400"></i>
                            <span class="text-slate-300 text-sm">Mon - Fri: 9:00 AM - 6:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                <p class="text-slate-400 text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                    <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
