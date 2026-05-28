@extends('layouts.app')

@section('title', 'About Us')

@section('content')

    {{-- Hero Banner --}}
    <section class="bg-slate-900 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-1.5 bg-blue-600/10 border border-blue-500/20 text-blue-400 text-sm font-medium rounded-full mb-6">
                About Us
            </span>
            <h1 class="text-4xl sm:text-5xl font-bold text-white mb-6">About {{ config('app.name') }}</h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto leading-relaxed">
                With over 15 years of experience, we have been helping families and investors find their perfect properties. Our commitment to excellence drives everything we do.
            </p>
        </div>
    </section>

    {{-- Our Story --}}
    <section class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 text-sm font-medium rounded-full mb-6">Our Story</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-6">Building Dreams Since 2010</h2>
                    <p class="text-slate-600 text-base leading-relaxed mb-6">
                        {{ config('app.name') }} was founded with a simple mission: to make property booking a seamless, transparent, and rewarding experience. What started as a small team of passionate agents has grown into one of the most trusted names in real estate.
                    </p>
                    <p class="text-slate-600 text-base leading-relaxed mb-6">
                        Over the years, we have helped thousands of families find their dream homes, assisted investors in building profitable portfolios, and guided businesses to the perfect commercial spaces. Our success is built on a foundation of trust, expertise, and an unwavering commitment to our clients.
                    </p>
                    <p class="text-slate-600 text-base leading-relaxed mb-8">
                        Today, with a team of over 50 expert agents and a portfolio of more than 1,200 properties, we continue to set the standard for excellence in the real estate industry.
                    </p>
                    <a href="{{ route('contact.create') }}"
                       class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                        Get in Touch
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="bg-slate-200 rounded-2xl h-96 lg:h-[500px] flex items-center justify-center">
                    <div class="text-center">
                        <i class="fa-solid fa-building-columns text-slate-400 text-6xl mb-4"></i>
                        <p class="text-slate-400 text-sm">Our Office</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Our Impact in Numbers</h2>
                <p class="text-slate-600 text-lg max-w-2xl mx-auto">We let our results speak for themselves. Here is what we have achieved.</p>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-building text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-slate-900 mb-2">1,200+</p>
                    <p class="text-slate-600 text-sm">Properties Listed</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-users text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-slate-900 mb-2">850+</p>
                    <p class="text-slate-600 text-sm">Satisfied Clients</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-star text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-slate-900 mb-2">15+</p>
                    <p class="text-slate-600 text-sm">Years of Experience</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-user-tie text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-3xl sm:text-4xl font-bold text-slate-900 mb-2">50+</p>
                    <p class="text-slate-600 text-sm">Expert Agents</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Our Values --}}
    <section class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 text-sm font-medium rounded-full mb-4">Our Values</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">What We Stand For</h2>
                <p class="text-slate-600 text-lg max-w-2xl mx-auto">Our core values define who we are and guide every decision we make.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-8 text-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-scale-balanced text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Integrity</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        We operate with complete honesty and transparency in every transaction. Our clients trust us because we always put their interests first and maintain the highest ethical standards.
                    </p>
                </div>
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-8 text-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-trophy text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Excellence</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        We strive for excellence in everything we do, from property selection to client service. Our team continuously improves to deliver the best possible experience for our clients.
                    </p>
                </div>
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-8 text-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-handshake text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Trust</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Trust is the foundation of our business. We build lasting relationships with our clients through reliability, consistency, and a genuine commitment to their success.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Team CTA --}}
    <section class="bg-slate-900 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <i class="fa-solid fa-people-group text-blue-400 text-4xl mb-6"></i>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Join Our Growing Team</h2>
                <p class="text-slate-300 text-lg mb-8 leading-relaxed">
                    We are always looking for talented and passionate individuals to join our team. If you share our values and love real estate, we would love to hear from you.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('contact.create') }}"
                       class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                        Contact Us
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                    <a href="{{ route('properties.index') }}"
                       class="inline-flex items-center justify-center px-8 py-4 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition-colors border border-white/20">
                        View Properties
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
