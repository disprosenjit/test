@extends('layouts.app')

@section('title', 'Special Request')

@section('content')
    <!-- Page Header -->
    <section class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white font-[Inter]">Submit a Special Request</h1>
            <p class="mt-3 text-slate-300 text-lg">Can't find what you're looking for? Tell us your requirements and we'll find the perfect property for you.</p>
            <nav class="mt-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ url('/') }}" class="text-slate-300 hover:text-white transition-colors">Home</a></li>
                    <li><span class="text-slate-500">/</span></li>
                    <li><span class="text-blue-400">Special Request</span></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Form Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Your Requirements</h2>
                <p class="text-slate-600 mb-8">Fill in the details below and our team will get back to you with matching properties.</p>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('special-requests.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Name & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="John Doe">
                                @error('name')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="john@example.com">
                                @error('email')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="+1 (555) 000-0000">
                            @error('phone')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Property Type Preference -->
                        <div>
                            <label for="property_type_preference" class="block text-sm font-medium text-slate-700 mb-1.5">Property Type Preference</label>
                            <select id="property_type_preference" name="property_type_preference" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                                <option value="">Select a type</option>
                                <option value="residential" {{ old('property_type_preference') == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('property_type_preference') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="land" {{ old('property_type_preference') == 'land' ? 'selected' : '' }}>Land</option>
                            </select>
                            @error('property_type_preference')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location Preference -->
                        <div>
                            <label for="location_preference" class="block text-sm font-medium text-slate-700 mb-1.5">Location Preference</label>
                            <input type="text" id="location_preference" name="location_preference" value="{{ old('location_preference') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="e.g., Downtown, Suburbs, Waterfront">
                            @error('location_preference')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Budget Range -->
                        <div>
                            <label for="budget_range" class="block text-sm font-medium text-slate-700 mb-1.5">Budget Range</label>
                            <input type="text" id="budget_range" name="budget_range" value="{{ old('budget_range') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="e.g., $200,000 - $500,000">
                            @error('budget_range')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Request Details -->
                        <div>
                            <label for="request_details" class="block text-sm font-medium text-slate-700 mb-1.5">Request Details</label>
                            <textarea id="request_details" name="request_details" rows="5" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none" placeholder="Describe your ideal property, including any specific features, requirements, or preferences...">{{ old('request_details') }}</textarea>
                            @error('request_details')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
