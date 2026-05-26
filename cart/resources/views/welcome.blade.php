@extends('layouts.app')

@section('title', 'Home - Ship Spare Parts Store')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12 md:py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Ship Spare Parts & Heavy-Duty Machinery</h1>
        <p class="text-xl md:text-2xl text-blue-100 mb-8">Quality OEM and aftermarket parts for marine vessels and industrial equipment</p>
        
        <!-- Search Bar -->
        <div class="flex">
            <input type="text" placeholder="Search parts by name or SKU..." class="flex-1 px-4 py-3 rounded-l-lg focus:outline-none text-gray-800" />
            <button class="bg-orange-600 hover:bg-orange-700 px-6 py-3 rounded-r-lg font-semibold">Search</button>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12">
        <h2 class="text-3xl font-bold mb-2">Featured Products</h2>
        <p class="text-gray-600">Discover our most popular marine and industrial spare parts</p>
    </div>

    <x-product-grid 
        :products="$products"
        columnsClass="grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
        gapClass="gap-6"
        :showPagination="true"
        :showBrand="true"
        :showSku="true"
        :showRating="false"
    />
</div>

<!-- Categories Section -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-8">Shop by Category</h2>
        
        @php
            $categories = \App\Models\Commerce\Category::all();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @forelse($categories as $category)
                <a href="/products?category_id={{ $category->id }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow text-center">
                    <div class="text-4xl mb-3">⚙️</div>
                    <h3 class="font-semibold text-gray-800">{{ $category->name }}</h3>
                </a>
            @empty
                <p class="text-gray-600">No categories available</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Brands Section -->
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-8">Top Brands</h2>
        
        @php
            $brands = \App\Models\Commerce\Brand::all();
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @forelse($brands as $brand)
                <a href="/products?brand_id={{ $brand->id }}" class="bg-gray-100 hover:bg-gray-200 p-6 rounded-lg text-center transition-colors">
                    <h3 class="font-semibold text-gray-800">{{ $brand->name }}</h3>
                </a>
            @empty
                <p class="text-gray-600">No brands available</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Why Choose Us Section -->
<div class="bg-blue-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-8 text-center">Why Choose Us?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-4xl mb-3">✓</div>
                <h3 class="font-semibold mb-2">Genuine Parts</h3>
                <p class="text-gray-600 text-sm">100% authentic OEM and quality aftermarket parts</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-3">📦</div>
                <h3 class="font-semibold mb-2">Fast Shipping</h3>
                <p class="text-gray-600 text-sm">2-3 business days delivery across India</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-3">💳</div>
                <h3 class="font-semibold mb-2">Multiple Payment Options</h3>
                <p class="text-gray-600 text-sm">Bank transfer, UPI, cards, and corporate checks</p>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-3">🎧</div>
                <h3 class="font-semibold mb-2">Expert Support</h3>
                <p class="text-gray-600 text-sm">24/7 customer support for technical queries</p>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Stay Updated</h2>
        <p class="mb-6">Subscribe to our newsletter for latest products and deals</p>
        
        <form class="flex gap-2">
            <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-lg focus:outline-none text-gray-800" required />
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 px-8 py-3 rounded-lg font-semibold">Subscribe</button>
        </form>
    </div>
</div>
@endsection
