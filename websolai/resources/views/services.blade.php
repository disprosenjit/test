@extends('layouts.app')

@section('title', 'Our Services — WebsolAI')
@section('meta_description', 'Explore WebsolAI\'s full range of services: web development, mobile app development, UI/UX design, API integration, e-commerce, and cloud solutions.')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-slate-950 to-indigo-950 text-white py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-indigo-400 font-semibold text-sm uppercase tracking-widest">What We Offer</span>
        <h1 class="text-5xl sm:text-6xl font-bold mt-4 mb-6 leading-tight">
            Full-Spectrum <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Digital Services</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
            From concept to launch and beyond, we offer every service your digital product needs to succeed — all under one roof.
        </p>
    </div>
</section>

{{-- Services Detail --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Web Development --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-24 pb-24 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-sm font-semibold mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Web Development
                </div>
                <h2 class="text-4xl font-bold text-slate-900 mb-6">Websites That Work As Hard As You Do</h2>
                <p class="text-slate-500 text-lg leading-relaxed mb-8">
                    We build performant, accessible, and beautiful websites tailored to your specific business needs. Whether you need a marketing site, a web app, a portal, or a SaaS platform, we have the expertise to deliver.
                </p>
                <ul class="space-y-4">
                    @foreach(['Corporate & Marketing Websites', 'Web Applications & Portals', 'SaaS Platforms', 'Progressive Web Apps (PWA)', 'CMS Development (WordPress, Headless)', 'Performance Optimisation & Audits'] as $item)
                    <li class="flex items-center gap-3 text-slate-600">
                        <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gradient-to-br from-indigo-50 to-slate-100 rounded-3xl p-10 flex items-center justify-center min-h-64">
                <svg class="w-40 h-40 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        {{-- Mobile App Development --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-24 pb-24 border-b border-slate-100">
            <div class="order-2 lg:order-1 bg-gradient-to-br from-violet-50 to-slate-100 rounded-3xl p-10 flex items-center justify-center min-h-64">
                <svg class="w-40 h-40 text-violet-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="order-1 lg:order-2">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-50 text-violet-600 text-sm font-semibold mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Mobile App Development
                </div>
                <h2 class="text-4xl font-bold text-slate-900 mb-6">Apps Your Users Will Love</h2>
                <p class="text-slate-500 text-lg leading-relaxed mb-8">
                    From MVP to enterprise-grade, we develop mobile applications that deliver engaging experiences across iOS and Android. We work with both native technologies and cross-platform frameworks to find the right fit for your project.
                </p>
                <ul class="space-y-4">
                    @foreach(['iOS App Development (Swift)', 'Android App Development (Kotlin)', 'Cross-Platform Apps (React Native, Flutter)', 'App Store Submission & Optimisation', 'Backend & API Development', 'Push Notifications & Analytics Integration'] as $item)
                    <li class="flex items-center gap-3 text-slate-600">
                        <svg class="w-5 h-5 text-violet-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- UI/UX Design --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-24 pb-24 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-pink-50 text-pink-600 text-sm font-semibold mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                    UI/UX Design
                </div>
                <h2 class="text-4xl font-bold text-slate-900 mb-6">Design That Converts and Delights</h2>
                <p class="text-slate-500 text-lg leading-relaxed mb-8">
                    Great design is more than aesthetics — it's about creating intuitive experiences that guide users toward meaningful actions. Our design team combines research, strategy, and craft to produce interfaces that both look stunning and perform.
                </p>
                <ul class="space-y-4">
                    @foreach(['User Research & Personas', 'Wireframing & Prototyping', 'Visual & Brand Design', 'Design Systems', 'Usability Testing', 'Responsive & Accessible Design'] as $item)
                    <li class="flex items-center gap-3 text-slate-600">
                        <svg class="w-5 h-5 text-pink-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gradient-to-br from-pink-50 to-slate-100 rounded-3xl p-10 flex items-center justify-center min-h-64">
                <svg class="w-40 h-40 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
            </div>
        </div>

        {{-- E-Commerce & API --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="p-8 rounded-2xl border border-slate-200 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">E-Commerce Solutions</h3>
                <p class="text-slate-500 leading-relaxed mb-5">Full-featured online stores with secure checkout, inventory management, and analytics. We build on Shopify, WooCommerce, or fully custom platforms.</p>
                <ul class="space-y-2 text-sm text-slate-500">
                    @foreach(['Custom E-Commerce Platforms', 'Shopify & WooCommerce', 'Payment Gateway Integration', 'Inventory & Order Management', 'Conversion Rate Optimisation'] as $item)
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="p-8 rounded-2xl border border-slate-200 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">API Development & Integration</h3>
                <p class="text-slate-500 leading-relaxed mb-5">We design, build, and document RESTful and GraphQL APIs, and seamlessly integrate third-party services to automate and enhance your workflows.</p>
                <ul class="space-y-2 text-sm text-slate-500">
                    @foreach(['REST & GraphQL API Design', 'Third-Party Integrations', 'Payment & CRM Systems', 'Authentication & Security', 'API Documentation'] as $item)
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="p-8 rounded-2xl border border-slate-200 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Cloud & DevOps</h3>
                <p class="text-slate-500 leading-relaxed mb-5">We architect and manage scalable cloud infrastructure on AWS, GCP, and Azure. CI/CD pipelines, containerisation, and monitoring to keep your apps healthy.</p>
                <ul class="space-y-2 text-sm text-slate-500">
                    @foreach(['AWS, GCP & Azure', 'CI/CD Pipeline Setup', 'Docker & Kubernetes', 'Server Monitoring & Alerting', 'Database Management'] as $item)
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="p-8 rounded-2xl border border-slate-200 hover:border-indigo-200 hover:shadow-lg transition-all">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Maintenance & Support</h3>
                <p class="text-slate-500 leading-relaxed mb-5">Post-launch peace of mind. We offer flexible support packages to keep your digital products secure, up to date, and performing at their best.</p>
                <ul class="space-y-2 text-sm text-slate-500">
                    @foreach(['Bug Fixes & Patches', 'Security Updates', 'Performance Monitoring', 'Feature Enhancements', 'Dedicated Support Plans'] as $item)
                    <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Tech Stack --}}
<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-widest">Our Tech Stack</span>
            <h2 class="text-4xl font-bold text-slate-900 mt-3 mb-4">Built With Best-in-Class Technologies</h2>
            <p class="text-slate-500 text-lg">We work with the tools best suited to your project — always choosing proven, industry-standard technologies.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach(['React', 'Vue.js', 'Laravel', 'Node.js', 'Flutter', 'React Native', 'Swift', 'Kotlin', 'TypeScript', 'Next.js', 'PostgreSQL', 'AWS'] as $tech)
            <div class="bg-white rounded-xl px-4 py-5 text-center border border-slate-200 hover:border-indigo-200 hover:shadow-md transition-all">
                <div class="font-semibold text-slate-700 text-sm">{{ $tech }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 bg-gradient-to-br from-indigo-600 to-violet-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-5">Not Sure What You Need?</h2>
        <p class="text-indigo-100 text-xl mb-8">Let's have a chat. We'll help you figure out the right solution for your goals and budget.</p>
        <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-white text-indigo-600 font-semibold hover:bg-indigo-50 transition-all shadow-lg hover:-translate-y-0.5">
            Book a Free Consultation
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

@endsection
