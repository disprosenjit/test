@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- Hero Section --}}
    <section class="bg-slate-900 min-h-[85vh] flex items-center relative overflow-hidden">
        <div class="absolute inset-0 bg-slate-800/30"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-10 w-full">
            <div class="max-w-3xl">
                <span class="inline-block px-4 py-1.5 bg-blue-600/10 border border-blue-500/20 text-blue-400 text-sm font-medium rounded-full mb-6">
                    Trusted by 850+ Happy Clients
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    Find Your Dream Property Today
                </h1>
                <p class="text-lg sm:text-xl text-slate-300 leading-relaxed mb-10 max-w-2xl">
                    Discover exceptional properties tailored to your lifestyle. From luxury homes to smart investments, we make your real estate journey seamless and rewarding.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 mb-16">
                    <a href="{{ route('properties.index') }}"
                       class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors text-base">
                        Explore Properties
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                    <a href="{{ route('contact.create') }}"
                       class="inline-flex items-center justify-center px-8 py-4 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition-colors text-base border border-white/20">
                        Free Consultation
                    </a>
                </div>
            </div>

            {{-- Trust Stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 pt-8 border-t border-slate-700/50">
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">1,200+</p>
                    <p class="text-slate-400 text-sm mt-1">Properties Listed</p>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">850+</p>
                    <p class="text-slate-400 text-sm mt-1">Happy Clients</p>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">15+</p>
                    <p class="text-slate-400 text-sm mt-1">Years Experience</p>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">50+</p>
                    <p class="text-slate-400 text-sm mt-1">Expert Agents</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Search Section --}}
    <section class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Search Properties</h2>
                <p class="text-slate-600 text-lg max-w-2xl mx-auto">Find the perfect property by filtering through our extensive listings.</p>
            </div>
            <form action="{{ route('properties.filter') }}" method="GET"
                  class="bg-slate-50 border border-slate-200 rounded-2xl p-6 sm:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-2">Property Type</label>
                        <select name="type" id="type"
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                            <option value="">All Types</option>
                            <option value="house">House</option>
                            <option value="apartment">Apartment</option>
                            <option value="villa">Villa</option>
                            <option value="commercial">Commercial</option>
                            <option value="land">Land</option>
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-slate-700 mb-2">Location</label>
                        <input type="text" name="location" id="location" placeholder="City or neighborhood"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-slate-700 mb-2">Min Price</label>
                        <input type="number" name="min_price" id="min_price" placeholder="$0"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-slate-700 mb-2">Max Price</label>
                        <input type="number" name="max_price" id="max_price" placeholder="No limit"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                            class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>
                        Search Properties
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- Featured Properties --}}
    <section class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 text-sm font-medium rounded-full mb-4">Featured</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Featured Properties</h2>
                <p class="text-slate-600 text-lg max-w-2xl mx-auto">Hand-picked properties that represent the best value and quality in the market.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredProperties as $property)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        {{-- Image --}}
                        <div class="relative h-56 bg-slate-100 overflow-hidden">
                            @if($property->images->count())
                                <img src="{{ asset($property->images->sortBy('order')->first()->image_path) }}"
                                     alt="{{ $property->images->sortBy('order')->first()->alt_text ?? $property->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-image text-slate-300 text-4xl"></i>
                                </div>
                            @endif
                            @if($property->is_featured)
                                <span class="absolute top-4 left-4 px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-lg">Featured</span>
                            @endif
                            <span class="absolute top-4 right-4 px-3 py-1 bg-slate-900/80 text-white text-xs font-semibold rounded-lg capitalize">{{ $property->type }}</span>
                        </div>
                        {{-- Content --}}
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ $property->title }}</h3>
                            <p class="text-slate-600 text-sm flex items-center mb-4">
                                <i class="fa-solid fa-location-dot text-blue-600 mr-2"></i>
                                {{ $property->location }}
                            </p>
                            <p class="text-2xl font-bold text-blue-600 mb-4">${{ number_format($property->price, 0) }}</p>
                            <div class="flex items-center justify-between text-sm text-slate-600 pt-4 border-t border-slate-100">
                                <span class="flex items-center">
                                    <i class="fa-solid fa-bed text-slate-400 mr-1.5"></i>
                                    {{ $property->bedrooms }} Beds
                                </span>
                                <span class="flex items-center">
                                    <i class="fa-solid fa-bath text-slate-400 mr-1.5"></i>
                                    {{ $property->bathrooms }} Baths
                                </span>
                                <span class="flex items-center">
                                    <i class="fa-solid fa-ruler-combined text-slate-400 mr-1.5"></i>
                                    {{ number_format($property->area) }} sqft
                                </span>
                            </div>
                            <a href="{{ route('properties.show', $property) }}"
                               class="mt-6 block text-center px-6 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <i class="fa-solid fa-building text-slate-300 text-5xl mb-4"></i>
                        <p class="text-slate-600 text-lg">No featured properties available at the moment.</p>
                        <a href="{{ route('properties.index') }}" class="inline-flex items-center mt-4 text-blue-600 font-medium hover:text-blue-700">
                            Browse All Properties <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Latest Properties --}}
    <section class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 text-sm font-medium rounded-full mb-4">New Listings</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Latest Properties</h2>
                <p class="text-slate-600 text-lg max-w-2xl mx-auto">Explore our newest additions to the market, freshly listed and waiting for you.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($latestProperties as $property)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        {{-- Image --}}
                        <div class="relative h-56 bg-slate-100 overflow-hidden">
                            @if($property->images->count())
                                <img src="{{ asset($property->images->sortBy('order')->first()->image_path) }}"
                                     alt="{{ $property->images->sortBy('order')->first()->alt_text ?? $property->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-image text-slate-300 text-4xl"></i>
                                </div>
                            @endif
                            <span class="absolute top-4 right-4 px-3 py-1 bg-slate-900/80 text-white text-xs font-semibold rounded-lg capitalize">{{ $property->type }}</span>
                        </div>
                        {{-- Content --}}
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ $property->title }}</h3>
                            <p class="text-slate-600 text-sm flex items-center mb-4">
                                <i class="fa-solid fa-location-dot text-blue-600 mr-2"></i>
                                {{ $property->location }}
                            </p>
                            <p class="text-2xl font-bold text-blue-600 mb-4">${{ number_format($property->price, 0) }}</p>
                            <div class="flex items-center justify-between text-sm text-slate-600 pt-4 border-t border-slate-100">
                                <span class="flex items-center">
                                    <i class="fa-solid fa-bed text-slate-400 mr-1.5"></i>
                                    {{ $property->bedrooms }} Beds
                                </span>
                                <span class="flex items-center">
                                    <i class="fa-solid fa-bath text-slate-400 mr-1.5"></i>
                                    {{ $property->bathrooms }} Baths
                                </span>
                                <span class="flex items-center">
                                    <i class="fa-solid fa-ruler-combined text-slate-400 mr-1.5"></i>
                                    {{ number_format($property->area) }} sqft
                                </span>
                            </div>
                            <a href="{{ route('properties.show', $property) }}"
                               class="mt-6 block text-center px-6 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <i class="fa-solid fa-building text-slate-300 text-5xl mb-4"></i>
                        <p class="text-slate-600 text-lg">No properties listed yet. Check back soon!</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('properties.index') }}"
                   class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                    View All Properties
                    <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Stats Bar --}}
    <section class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-2">1,200+</p>
                    <p class="text-slate-400 text-sm">Properties</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-2">850+</p>
                    <p class="text-slate-400 text-sm">Happy Clients</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-2">95%</p>
                    <p class="text-slate-400 text-sm">Satisfaction Rate</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold text-white mb-2">24/7</p>
                    <p class="text-slate-400 text-sm">Support Available</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Special Request CTA --}}
    <section class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-3xl mx-auto">
                <i class="fa-solid fa-magnifying-glass-location text-blue-600 text-4xl mb-6"></i>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Can't Find Your Perfect Match?</h2>
                <p class="text-slate-600 text-lg mb-8 leading-relaxed">
                    Tell us exactly what you're looking for and our team of expert agents will find the perfect property tailored to your needs.
                </p>
                <a href="{{ route('special-requests.create') }}"
                   class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors text-base">
                    Submit a Special Request
                    <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Investment CTA --}}
    <section class="bg-blue-600 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-3xl mx-auto">
                <i class="fa-solid fa-chart-line text-white/80 text-4xl mb-6"></i>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Invest in Real Estate</h2>
                <p class="text-blue-100 text-lg mb-8 leading-relaxed">
                    Discover lucrative investment opportunities with proven returns. Our expert team guides you through every step of your investment journey.
                </p>
                <a href="{{ route('investors.index') }}"
                   class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition-colors text-base">
                    Explore Investment Opportunities
                    <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 text-sm font-medium rounded-full mb-4">Why Us</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">Why Choose Real Estate Pro</h2>
                <p class="text-slate-600 text-lg max-w-2xl mx-auto">We stand apart with our commitment to excellence, transparency, and client satisfaction.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-shield-halved text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-3">Trusted & Verified</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">All our properties are thoroughly verified and documented, ensuring complete transparency in every transaction.</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-handshake text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-3">Expert Negotiation</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Our seasoned agents negotiate the best deals on your behalf, saving you time and money on every property.</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-3">24/7 Support</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Our dedicated support team is available around the clock to answer your questions and assist with any needs.</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-magnifying-glass text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-3">Wide Selection</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Browse through hundreds of listings across all property types, from cozy apartments to luxury estates.</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-dollar-sign text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-3">Best Value</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">We ensure competitive pricing and help you find properties that deliver the best return on your investment.</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-file-contract text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-3">Legal Assistance</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Our legal experts handle all paperwork and compliance, making the buying and selling process stress-free.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="bg-slate-900 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Get In Touch</h2>
                <p class="text-slate-300 text-lg max-w-2xl mx-auto">Ready to find your perfect property? Reach out to us through any of these channels.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <a href="{{ route('contact.create') }}"
                   class="bg-slate-800 rounded-2xl p-8 text-center hover:bg-slate-700 transition-colors group">
                    <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-600/20 transition-colors">
                        <i class="fa-solid fa-envelope text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Email Us</h3>
                    <p class="text-slate-400 text-sm">info@realestatepro.com</p>
                </a>
                <div class="bg-slate-800 rounded-2xl p-8 text-center">
                    <div class="w-14 h-14 bg-blue-600/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-phone text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Call Us</h3>
                    <p class="text-slate-400 text-sm">+1 (555) 123-4567</p>
                </div>
                <a href="https://wa.me/15551234567" target="_blank" rel="noopener noreferrer"
                   class="bg-slate-800 rounded-2xl p-8 text-center hover:bg-slate-700 transition-colors group">
                    <div class="w-14 h-14 bg-green-600/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-600/20 transition-colors">
                        <i class="fa-brands fa-whatsapp text-green-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">WhatsApp</h3>
                    <p class="text-slate-400 text-sm">Chat with us now</p>
                </a>
            </div>
        </div>
    </section>

@endsection
