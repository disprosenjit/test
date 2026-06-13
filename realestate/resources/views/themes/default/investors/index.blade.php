@extends('layouts.app')

@section('title', 'Investment Opportunities')

@section('content')
    <!-- Hero Section -->
    <section class="bg-slate-900 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white font-[Inter] mb-6">Investment Opportunities</h1>
            <p class="text-lg sm:text-xl text-slate-300 max-w-3xl mx-auto">Discover premium real estate investment opportunities with strong returns and professional management. Build your portfolio with confidence.</p>
            <nav class="mt-8" aria-label="Breadcrumb">
                <ol class="flex items-center justify-center space-x-2 text-sm">
                    <li><a href="{{ url('/') }}" class="text-slate-300 hover:text-white transition-colors">Home</a></li>
                    <li><span class="text-slate-500">/</span></li>
                    <li><span class="text-blue-400">Investment Opportunities</span></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Why Invest Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 font-[Inter] mb-4">Why Invest With Us</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">We offer a range of benefits that make real estate investment accessible, profitable, and hassle-free.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- High Returns -->
                <div class="bg-slate-50 rounded-xl border border-slate-200 p-8 text-center hover:shadow-sm transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">High Returns</h3>
                    <p class="text-slate-600 leading-relaxed">Enjoy competitive returns on your investment with carefully selected properties in high-growth markets. Our track record speaks for itself with consistent above-market performance.</p>
                </div>

                <!-- Professional Management -->
                <div class="bg-slate-50 rounded-xl border border-slate-200 p-8 text-center hover:shadow-sm transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users-cog text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Professional Management</h3>
                    <p class="text-slate-600 leading-relaxed">Our experienced team handles all aspects of property management, from tenant screening to maintenance, so you can enjoy passive income without the hassle.</p>
                </div>

                <!-- Diversification -->
                <div class="bg-slate-50 rounded-xl border border-slate-200 p-8 text-center hover:shadow-sm transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-th-large text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Diversification</h3>
                    <p class="text-slate-600 leading-relaxed">Spread your investments across residential, commercial, and land properties to minimize risk and maximize growth potential across different market segments.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Investment Types Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 font-[Inter] mb-4">Investment Types</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Choose from a variety of investment types tailored to your goals and risk appetite.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Residential -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-slate-100 h-48 flex items-center justify-center">
                        <i class="fas fa-home text-slate-300 text-5xl"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-900 mb-3">Residential Properties</h3>
                        <p class="text-slate-600 leading-relaxed mb-4">Invest in single-family homes, apartments, and condominiums in prime locations. Benefit from steady rental income and long-term property appreciation.</p>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li class="flex items-center"><i class="fas fa-check text-blue-600 mr-2"></i>Steady rental income</li>
                            <li class="flex items-center"><i class="fas fa-check text-blue-600 mr-2"></i>Long-term appreciation</li>
                            <li class="flex items-center"><i class="fas fa-check text-blue-600 mr-2"></i>High demand markets</li>
                        </ul>
                    </div>
                </div>

                <!-- Commercial -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-slate-100 h-48 flex items-center justify-center">
                        <i class="fas fa-building text-slate-300 text-5xl"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-900 mb-3">Commercial Properties</h3>
                        <p class="text-slate-600 leading-relaxed mb-4">Explore office spaces, retail locations, and industrial properties. Commercial real estate offers higher yields and longer lease terms for stable cash flow.</p>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li class="flex items-center"><i class="fas fa-check text-blue-600 mr-2"></i>Higher yield potential</li>
                            <li class="flex items-center"><i class="fas fa-check text-blue-600 mr-2"></i>Long-term leases</li>
                            <li class="flex items-center"><i class="fas fa-check text-blue-600 mr-2"></i>Professional tenants</li>
                        </ul>
                    </div>
                </div>

                <!-- Land Development -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-slate-100 h-48 flex items-center justify-center">
                        <i class="fas fa-mountain text-slate-300 text-5xl"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-900 mb-3">Land Development</h3>
                        <p class="text-slate-600 leading-relaxed mb-4">Acquire raw land in emerging areas with high development potential. Benefit from significant value increases as surrounding infrastructure develops.</p>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li class="flex items-center"><i class="fas fa-check text-blue-600 mr-2"></i>High growth potential</li>
                            <li class="flex items-center"><i class="fas fa-check text-blue-600 mr-2"></i>Flexible development</li>
                            <li class="flex items-center"><i class="fas fa-check text-blue-600 mr-2"></i>Strategic locations</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-blue-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white font-[Inter] mb-4">Ready to Invest?</h2>
            <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">Take the first step towards building your real estate portfolio. Our investment team is ready to help you find the perfect opportunity.</p>
            <a href="{{ route('investors.create') }}" class="inline-block bg-white hover:bg-slate-100 text-blue-600 font-semibold py-3.5 px-10 rounded-lg transition-colors focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 text-lg">
                <i class="fas fa-arrow-right mr-2"></i>Submit an Inquiry
            </a>
        </div>
    </section>
@endsection
