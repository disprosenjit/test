@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <!-- Page Header -->
    <section class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white font-[Inter]">Get In Touch</h1>
            <p class="mt-3 text-slate-300 text-lg">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            <nav class="mt-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ url('/') }}" class="text-slate-300 hover:text-white transition-colors">Home</a></li>
                    <li><span class="text-slate-500">/</span></li>
                    <li><span class="text-blue-400">Contact</span></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Content -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Send Us a Message</h2>

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="John Doe">
                                    @error('name')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="john@example.com">
                                    @error('email')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="+1 (555) 000-0000">
                                    @error('phone')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Subject -->
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-slate-700 mb-1.5">Subject</label>
                                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="How can we help?">
                                    @error('subject')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="mt-6">
                                <label for="message" class="block text-sm font-medium text-slate-700 mb-1.5">Message</label>
                                <textarea id="message" name="message" rows="6" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none" placeholder="Tell us more about your inquiry...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="mt-6">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <i class="fas fa-paper-plane mr-2"></i>Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right: Contact Info Cards -->
                <div class="space-y-6">
                    <!-- Phone -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 mb-1">Phone</h3>
                                <p class="text-slate-600">+1 (555) 123-4567</p>
                                <p class="text-slate-600">+1 (555) 765-4321</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 mb-1">Email</h3>
                                <p class="text-slate-600">info@realestate.com</p>
                                <p class="text-slate-600">support@realestate.com</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 mb-1">Address</h3>
                                <p class="text-slate-600">123 Real Estate Blvd,<br>Suite 100, New York, NY 10001</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 mb-1">Business Hours</h3>
                                <p class="text-slate-600">Mon - Fri: 9:00 AM - 6:00 PM</p>
                                <p class="text-slate-600">Sat: 10:00 AM - 4:00 PM</p>
                                <p class="text-slate-600">Sun: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
