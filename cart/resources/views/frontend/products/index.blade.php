@extends('layouts.app')

@section('title', 'Products - Ship Spare Parts Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <aside class="lg:col-span-1">
            <form action="/products" method="GET" id="filter-form" class="space-y-4">
                <!-- Search -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <label class="block text-sm font-semibold mb-2">Search</label>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Part number..."
                        class="w-full px-3 py-2 border rounded-lg text-sm"
                        value="{{ request('search') }}"
                    />
                </div>

                <!-- Brand Filter -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <label class="block text-sm font-semibold mb-2">Brand</label>
                    <select name="brand_id" class="w-full px-3 py-2 border rounded-lg text-sm" onchange="document.getElementById('filter-form').submit()">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <label class="block text-sm font-semibold mb-2">Category</label>
                    <select name="category_id" class="w-full px-3 py-2 border rounded-lg text-sm" onchange="document.getElementById('filter-form').submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <label class="block text-sm font-semibold mb-2">Price Range</label>
                    <div class="space-y-2">
                        <input 
                            type="number" 
                            name="min_price" 
                            placeholder="Min ₹"
                            class="w-full px-3 py-2 border rounded-lg text-sm"
                            value="{{ request('min_price') }}"
                        />
                        <input 
                            type="number" 
                            name="max_price" 
                            placeholder="Max ₹"
                            class="w-full px-3 py-2 border rounded-lg text-sm"
                            value="{{ request('max_price') }}"
                        />
                    </div>
                </div>

                <!-- Stock Filter -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="in_stock" 
                            value="1" 
                            class="mr-2"
                            {{ request('in_stock') ? 'checked' : '' }}
                        />
                        <span class="text-sm">In Stock Only</span>
                    </label>
                </div>

                <!-- Sort -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <label class="block text-sm font-semibold mb-2">Sort By</label>
                    <select name="sort_by" class="w-full px-3 py-2 border rounded-lg text-sm" onchange="document.getElementById('filter-form').submit()">
                        <option value="relevance" {{ request('sort_by') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                        <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>

                <!-- Apply Button -->
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    Apply Filters
                </button>
            </form>
        </aside>

        <!-- Products Grid -->
        <main class="lg:col-span-3">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold">Spare Parts Catalog</h1>
                <p class="text-gray-600">{{ $products->total() }} products found</p>
            </div>

            <!-- Products -->
            <x-product-grid 
                :products="$products"
                columnsClass="grid-cols-1 sm:grid-cols-2 lg:grid-cols-3"
                gapClass="gap-4"
                :showPagination="true"
                :showBrand="false"
                :showSku="true"
                :showRating="true"
            />
        </main>
    </div>
</div>
@endsection
