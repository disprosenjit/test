@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Dashboard Header --}}
    <section class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-user text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white">Welcome back, {{ Auth::user()->name }}</h1>
                    <p class="text-slate-300 text-sm mt-1">Manage your account and explore your options.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Dashboard Content --}}
    <section class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Profile Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-user-pen text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">My Profile</h3>
                    <p class="text-slate-600 text-sm mb-6 leading-relaxed">View and update your personal information, email, and password settings.</p>
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center text-blue-600 text-sm font-semibold hover:text-blue-700 transition-colors">
                        Edit Profile
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>

                {{-- Properties Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-building text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Browse Properties</h3>
                    <p class="text-slate-600 text-sm mb-6 leading-relaxed">Explore our full catalog of available properties, from homes to commercial spaces.</p>
                    <a href="{{ route('properties.index') }}"
                       class="inline-flex items-center text-blue-600 text-sm font-semibold hover:text-blue-700 transition-colors">
                        View Properties
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>

                {{-- Special Requests Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-clipboard-list text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Special Requests</h3>
                    <p class="text-slate-600 text-sm mb-6 leading-relaxed">Submit a special property request and let our agents find the perfect match for you.</p>
                    <a href="{{ route('special-requests.create') }}"
                       class="inline-flex items-center text-blue-600 text-sm font-semibold hover:text-blue-700 transition-colors">
                        Submit Request
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>

                {{-- Contact Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-envelope text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Contact Us</h3>
                    <p class="text-slate-600 text-sm mb-6 leading-relaxed">Have questions or need assistance? Get in touch with our team of experts.</p>
                    <a href="{{ route('contact.create') }}"
                       class="inline-flex items-center text-blue-600 text-sm font-semibold hover:text-blue-700 transition-colors">
                        Send Message
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>

                {{-- Investments Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-chart-line text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Investments</h3>
                    <p class="text-slate-600 text-sm mb-6 leading-relaxed">Discover investment opportunities and learn how real estate can grow your portfolio.</p>
                    <a href="{{ route('investors.index') }}"
                       class="inline-flex items-center text-blue-600 text-sm font-semibold hover:text-blue-700 transition-colors">
                        Explore Investments
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>

                {{-- Admin Panel Card (conditional) --}}
                @if(Auth::user()->is_admin ?? false)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                        <div class="w-14 h-14 bg-slate-900 rounded-xl flex items-center justify-center mb-6">
                            <i class="fa-solid fa-gauge-high text-white text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Admin Panel</h3>
                        <p class="text-slate-600 text-sm mb-6 leading-relaxed">Access the administration panel to manage properties, users, and site settings.</p>
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center text-blue-600 text-sm font-semibold hover:text-blue-700 transition-colors">
                            Go to Admin
                            <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @endif

            </div>

            {{-- Account Info --}}
            <div class="mt-12 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                <h3 class="text-lg font-semibold text-slate-900 mb-6">Account Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-slate-400 mb-1">Full Name</p>
                        <p class="text-slate-900 font-medium">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400 mb-1">Email Address</p>
                        <p class="text-slate-900 font-medium">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400 mb-1">Member Since</p>
                        <p class="text-slate-900 font-medium">{{ Auth::user()->created_at->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
