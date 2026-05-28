<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', __('Real Estate Pro')) - {{ __('Real Estate Pro') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        [dir="rtl"] body { text-align: right; }
        [dir="rtl"] .space-x-2 > :not([hidden]) ~ :not([hidden]),
        [dir="rtl"] .space-x-3 > :not([hidden]) ~ :not([hidden]),
        [dir="rtl"] .space-x-4 > :not([hidden]) ~ :not([hidden]),
        [dir="rtl"] .space-x-6 > :not([hidden]) ~ :not([hidden]),
        [dir="rtl"] .space-x-8 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }
        [dir="rtl"] .mr-1 { margin-right: 0; margin-left: 0.25rem; }
        [dir="rtl"] .mr-1\.5 { margin-right: 0; margin-left: 0.375rem; }
        [dir="rtl"] .mr-2 { margin-right: 0; margin-left: 0.5rem; }
        [dir="rtl"] .mr-3 { margin-right: 0; margin-left: 0.75rem; }
        [dir="rtl"] .ml-2 { margin-left: 0; margin-right: 0.5rem; }
        [dir="rtl"] .text-right { text-align: left; }
        [dir="rtl"] .rtl-text-right { text-align: right; }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased">

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-building text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-bold text-slate-900">{{ __('Real Estate Pro') }}</span>
                </a>

                {{-- Desktop Links --}}
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="{{ route('home') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('Home') }}
                    </a>
                    <a href="{{ route('properties.index') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('properties.*') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('Properties') }}
                    </a>
                    <a href="{{ route('about') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('About') }}
                    </a>
                    <a href="{{ route('investors.index') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('investors.*') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('Investors') }}
                    </a>
                    <a href="{{ route('contact.create') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('contact.*') ? 'text-blue-600' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ __('Contact') }}
                    </a>
                </div>

                <div class="hidden lg:flex items-center space-x-3">
                    @foreach($supportedLocales as $localeCode => $localeLabel)
                        <a href="{{ route('locale.switch', $localeCode) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors {{ $currentLocale === $localeCode ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                           hreflang="{{ $localeCode }}"
                           lang="{{ $localeCode }}">
                            {{ $localeLabel }}
                        </a>
                    @endforeach
                </div>

                {{-- CTA + Mobile Toggle --}}
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}"
                           class="hidden lg:inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('properties.index') }}"
                           class="hidden lg:inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                            {{ __('Find Property') }}
                        </a>
                    </a>
                </div>
                    @endauth

                    {{-- Mobile Menu Button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="lg:hidden p-2 text-slate-600 hover:text-slate-900 transition-colors"
                            aria-label="{{ __('Toggle menu') }}">
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
                   class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('properties.index') }}"
                   class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('properties.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ __('Properties') }}
                </a>
                <a href="{{ route('about') }}"
                   class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('about') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ __('About') }}
                </a>
                <a href="{{ route('investors.index') }}"
                   class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('investors.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ __('Investors') }}
                </a>
                <a href="{{ route('contact.create') }}"
                   class="block px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('contact.*') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ __('Contact') }}
                </a>
                <div class="grid grid-cols-2 gap-2 pt-2">
                    @foreach($supportedLocales as $localeCode => $localeLabel)
                        <a href="{{ route('locale.switch', $localeCode) }}"
                           class="px-3 py-2 rounded-xl text-center text-sm font-medium transition-colors {{ $currentLocale === $localeCode ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                           hreflang="{{ $localeCode }}"
                           lang="{{ $localeCode }}">
                            {{ $localeLabel }}
                        </a>
                    @endforeach
                </a>
                <div class="pt-2">
                    @auth
                        <a href="{{ route('admin.dashboard') }}"
                           class="block w-full text-center px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('properties.index') }}"
                           class="block w-full text-center px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                            {{ __('Find Property') }}
                        </a>
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
                        <span class="text-xl font-bold text-white">{{ __('Real Estate Pro') }}</span>
                    </a>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        {{ __('Your trusted partner in finding the perfect property. We bring expertise, integrity, and dedication to every transaction.') }}
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
                    <h3 class="text-lg font-semibold text-white mb-6">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('Home') }}</a></li>
                        <li><a href="{{ route('properties.index') }}" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('Properties') }}</a></li>
                        <li><a href="{{ route('about') }}" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('About Us') }}</a></li>
                        <li><a href="{{ route('investors.index') }}" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('Investors') }}</a></li>
                        <li><a href="{{ route('contact.create') }}" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('Contact') }}</a></li>
                    </ul>
                </div>

                {{-- Property Types --}}
                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">{{ __('Property Types') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('properties.index') }}?type=house" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('Houses') }}</a></li>
                        <li><a href="{{ route('properties.index') }}?type=apartment" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('Apartments') }}</a></li>
                        <li><a href="{{ route('properties.index') }}?type=commercial" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('Commercial') }}</a></li>
                        <li><a href="{{ route('properties.index') }}?type=land" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('Land') }}</a></li>
                        <li><a href="{{ route('properties.index') }}?type=villa" class="text-slate-300 hover:text-white text-sm transition-colors">{{ __('Villas') }}</a></li>
                    </ul>
                </div>

                {{-- Contact Info --}}
                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">{{ __('Contact Info') }}</h3>
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
                            <span class="text-slate-300 text-sm">{{ __('Mon - Fri: 9:00 AM - 6:00 PM') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                <p class="text-slate-400 text-sm">{{ __(':year Real Estate Pro. All rights reserved.', ['year' => '© '.date('Y')]) }}</p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">{{ __('Privacy Policy') }}</a>
                    <a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">{{ __('Terms of Service') }}</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
