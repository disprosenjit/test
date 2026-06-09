<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="@yield('meta_description', 'WebsolAI — Professional web and mobile app development agency. We build fast, scalable digital products tailored to your business.')">
    <title>@yield('title', 'WebsolAI — Web & Mobile App Development')</title>

    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/logo.png">
    <link rel="apple-touch-icon" href="/images/logo.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-800 antialiased">

    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 rounded-xl overflow-hidden shadow-md group-hover:shadow-indigo-300 transition-shadow flex-shrink-0">
                        <img src="/images/logo.png" alt="WebsolAI" class="w-full h-full object-cover object-top" style="object-position: 50% 15%;">
                    </div>
                    <span class="text-xl font-bold text-slate-900">WebSol<span class="text-indigo-600">AI</span></span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">Home</a>
                    <a href="{{ url('/about') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">About</a>
                    <a href="{{ url('/services') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">Services</a>
                    <a href="{{ url('/contact') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">Contact</a>
                </div>

                {{-- CTA + Mobile Toggle --}}
                <div class="flex items-center gap-3">
                    <a href="{{ url('/contact') }}" class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                        Get Started
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <button id="mobile-menu-btn" aria-label="Toggle menu" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                        <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
                <a href="{{ url('/') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">Home</a>
                <a href="{{ url('/about') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">About</a>
                <a href="{{ url('/services') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">Services</a>
                <a href="{{ url('/contact') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">Contact</a>
                <div class="pt-3 border-t border-slate-100 mt-3">
                    <a href="{{ url('/contact') }}" class="flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="pt-16">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-12">

                {{-- Brand --}}
                <div class="lg:col-span-2">
                    <a href="{{ url('/') }}" class="inline-block mb-5">
                        <img src="/images/logo.png" alt="WebsolAI" class="h-20 w-20 rounded-2xl object-cover shadow-lg shadow-indigo-900/40">
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6 max-w-xs">
                        We build exceptional websites and mobile applications that help businesses thrive in the digital world.
                    </p>
                    <?php
                        /*
                    ?>
                    <div class="flex gap-3">
                        <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-indigo-600 transition-colors">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" aria-label="LinkedIn" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-indigo-600 transition-colors">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="#" aria-label="Twitter" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-indigo-600 transition-colors">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" aria-label="Instagram" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-indigo-600 transition-colors">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                    <?php
                    */
                    ?>
                </div>

                {{-- Services --}}
                <div>
                    <h3 class="text-white font-semibold text-sm mb-5">Services</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ url('/services') }}" class="hover:text-white transition-colors">Web Development</a></li>
                        <li><a href="{{ url('/services') }}" class="hover:text-white transition-colors">Mobile Apps</a></li>
                        <li><a href="{{ url('/services') }}" class="hover:text-white transition-colors">UI/UX Design</a></li>
                        <li><a href="{{ url('/services') }}" class="hover:text-white transition-colors">API Integration</a></li>
                        <li><a href="{{ url('/services') }}" class="hover:text-white transition-colors">E-Commerce</a></li>
                        <li><a href="{{ url('/services') }}" class="hover:text-white transition-colors">Cloud & DevOps</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h3 class="text-white font-semibold text-sm mb-5">Company</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ url('/about') }}" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ url('/contact') }}" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                {{-- Legal --}}
                <div>
                    <h3 class="text-white font-semibold text-sm mb-5">Legal</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ url('/privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ url('/terms') }}" class="hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm text-slate-500">&copy; {{ date('Y') }} WebsolAI. All rights reserved.</p>
                <div class="flex gap-6 text-sm">
                    <a href="{{ url('/privacy') }}" class="text-slate-500 hover:text-slate-300 transition-colors">Privacy Policy</a>
                    <a href="{{ url('/terms') }}" class="text-slate-500 hover:text-slate-300 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Mobile Menu Script --}}
    <script>
        (function () {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('icon-open');
            const iconClose = document.getElementById('icon-close');
            btn.addEventListener('click', function () {
                const isHidden = menu.classList.toggle('hidden');
                iconOpen.classList.toggle('hidden', !isHidden);
                iconClose.classList.toggle('hidden', isHidden);
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
