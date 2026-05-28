@extends('layouts.app')

@section('title', __('Investment Inquiry'))

@section('content')
    <!-- Page Header -->
    <section class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white font-[Inter]">{{ __('Investment Inquiry') }}</h1>
            <p class="mt-3 text-slate-300 text-lg">{{ __('Tell us about your investment goals and we\'ll connect you with the right opportunities.') }}</p>
            <nav class="mt-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ url('/') }}" class="text-slate-300 hover:text-white transition-colors">{{ __('Home') }}</a></li>
                    <li><span class="text-slate-500">/</span></li>
                    <li><a href="{{ route('investors.index') }}" class="text-slate-300 hover:text-white transition-colors">{{ __('Investment Opportunities') }}</a></li>
                    <li><span class="text-slate-500">/</span></li>
                    <li><span class="text-blue-400">{{ __('Inquiry') }}</span></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Form Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Submit Your Inquiry') }}</h2>
                <p class="text-slate-600 mb-8">{{ __('Fill in the details below and our investment team will get back to you within 24 hours.') }}</p>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('investors.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Company Name & Contact Person -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Company Name') }}</label>
                                <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="{{ __('Your Company Ltd.') }}">
                                @error('company_name')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_person" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Contact Person') }}</label>
                                <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="{{ __('John Doe') }}">
                                @error('contact_person')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email & Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Email Address') }}</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="{{ __('john@company.com') }}">
                                @error('email')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Phone Number') }}</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="+1 (555) 000-0000">
                                @error('phone')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Investment Type -->
                        <div>
                            <label for="investment_type" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Investment Type') }}</label>
                            <select id="investment_type" name="investment_type" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                                <option value="">{{ __('Select investment type') }}</option>
                                <option value="residential" {{ old('investment_type') == 'residential' ? 'selected' : '' }}>{{ __('Residential') }}</option>
                                <option value="commercial" {{ old('investment_type') == 'commercial' ? 'selected' : '' }}>{{ __('Commercial') }}</option>
                                <option value="land" {{ old('investment_type') == 'land' ? 'selected' : '' }}>{{ __('Land') }}</option>
                                <option value="mixed" {{ old('investment_type') == 'mixed' ? 'selected' : '' }}>{{ __('Mixed') }}</option>
                            </select>
                            @error('investment_type')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preferred Location -->
                        <div>
                            <label for="preferred_location" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Preferred Location') }}</label>
                            <input type="text" id="preferred_location" name="preferred_location" value="{{ old('preferred_location') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="{{ __('e.g., Downtown, Suburbs, Waterfront') }}">
                            @error('preferred_location')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Investment Amount -->
                        <div>
                            <label for="investment_amount" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Investment Amount ($)') }}</label>
                            <input type="number" id="investment_amount" name="investment_amount" value="{{ old('investment_amount') }}" min="0" step="1000" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" placeholder="{{ __('e.g., 500000') }}">
                            @error('investment_amount')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Inquiry Details -->
                        <div>
                            <label for="inquiry_details" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Inquiry Details') }}</label>
                            <textarea id="inquiry_details" name="inquiry_details" rows="5" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none" placeholder="{{ __('Tell us about your investment goals, timeline, and any specific requirements...') }}">{{ old('inquiry_details') }}</textarea>
                            @error('inquiry_details')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-paper-plane mr-2"></i>{{ __('Submit Inquiry') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
