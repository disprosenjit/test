@props([
    'products' => collect(),
    'columnsClass' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4',
    'gapClass' => 'gap-6',
    'showPagination' => true,
    'showBrand' => true,
    'showSku' => true,
    'showRating' => false,
])

@if($products->count() > 0)
    <!-- Product Grid -->
    <div class="grid {{ $columnsClass }} {{ $gapClass }} mb-8">
        @foreach($products as $product)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
                <!-- Product Image -->
                <a href="/products/{{ $product->id }}" class="block">
                    <div class="bg-gray-200 h-48 flex items-center justify-center overflow-hidden">
                        @if(isset($product->images) && !empty($product->images))
                            @php
                                $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                                $image = $images[0] ?? null;
                            @endphp
                            @if($image)
                                <img 
                                    src="{{ $image }}" 
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover hover:scale-105 transition"
                                    loading="lazy"
                                />
                            @else
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-20 h-20" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 64 64">
                                        <circle cx="32" cy="32" r="10" stroke-width="2"/>
                                        <path stroke-width="2" d="M32 6v8M32 50v8M6 32h8M50 32h8M13.37 13.37l5.66 5.66M44.97 44.97l5.66 5.66M13.37 50.63l5.66-5.66M44.97 19.03l5.66-5.66"/>
                                        <circle cx="32" cy="32" r="5" fill="currentColor" opacity=".3"/>
                                    </svg>
                                    <span class="text-xs mt-2 font-medium">No Image</span>
                                </div>
                            @endif
                        @else
                            <div class="flex flex-col items-center text-gray-400">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 64 64">
                                    <circle cx="32" cy="32" r="10" stroke-width="2"/>
                                    <path stroke-width="2" d="M32 6v8M32 50v8M6 32h8M50 32h8M13.37 13.37l5.66 5.66M44.97 44.97l5.66 5.66M13.37 50.63l5.66-5.66M44.97 19.03l5.66-5.66"/>
                                    <circle cx="32" cy="32" r="5" fill="currentColor" opacity=".3"/>
                                </svg>
                                <span class="text-xs mt-2 font-medium">No Image</span>
                            </div>
                        @endif
                    </div>
                </a>

                <!-- Product Details -->
                <div class="p-4">
                    @if($showBrand && isset($product->brand))
                        <p class="text-xs text-gray-500 mb-1">{{ $product->brand->name ?? 'Generic' }}</p>
                    @endif

                    <a href="/products/{{ $product->id }}" class="hover:text-blue-600">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h3>
                    </a>
                    
                    @if($showSku)
                        <p class="text-xs text-gray-600 mb-2">SKU: <span class="font-mono">{{ $product->sku }}</span></p>
                    @endif

                    <!-- Rating (if enabled) -->
                    @if($showRating)
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400 text-xs">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < round($product->average_rating ?? 0))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs text-gray-600 ml-2">({{ $product->reviews_count ?? 0 }})</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">₹{{ number_format($product->price, 0) }}</p>
                            @if(isset($product->cost))
                                <p class="text-xs text-gray-500 line-through">₹{{ number_format($product->cost * 1.3, 0) }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-4">
                        @php
                            // Get available quantity using the model's method
                            $available = $product->getAvailableQuantity();
                        @endphp
                        @if($available > 10)
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">In Stock</span>
                        @elseif($available > 0)
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Low Stock</span>
                        @else
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="/products/{{ $product->id }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold text-sm">
                            View Details
                        </a>
                        @if($available > 0)
                            <button 
                                type="button"
                                onclick="addToCart({{ $product->id }})"
                                class="flex-1 bg-orange-600 hover:bg-orange-700 text-white py-2 rounded font-semibold text-sm transition"
                            >
                                Add to Cart
                            </button>
                        @else
                            <button 
                                type="button"
                                disabled
                                class="flex-1 bg-gray-400 text-white py-2 rounded font-semibold text-sm cursor-not-allowed opacity-50"
                            >
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($showPagination && method_exists($products, 'links'))
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    @endif
@else
    <div class="text-center py-12">
        <p class="text-gray-600 text-lg mb-4">No products available at the moment</p>
        <p class="text-gray-500">Please check back soon for updates</p>
    </div>
@endif
