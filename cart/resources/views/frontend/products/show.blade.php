@extends('layouts.app')

@section('title', $product->name . ' - Ship Spare Parts Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <a href="/" class="text-blue-600 hover:underline">Home</a>
        <span class="text-gray-600 mx-2">/</span>
        <a href="/products" class="text-blue-600 hover:underline">Products</a>
        <span class="text-gray-600 mx-2">/</span>
        <span class="text-gray-600">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Images Slideshow -->
        <div class="lg:col-span-1">
            <div class="sticky top-20">
                @php
                    // Product model casts images to array, so use directly
                    $images = is_array($product->images) ? $product->images : (json_decode($product->images, true) ?? []);
                    $hasMultiple = count($images) > 1;
                @endphp
                
                <!-- Main Slideshow Container -->
                <div class="bg-gray-200 rounded-lg overflow-hidden mb-4 relative group" id="slideshow-container">
                    <!-- Main Image/Video Display -->
                    <div class="relative w-full h-96 bg-gray-100">
                        @foreach($images as $index => $image)
                            <div class="slide fade absolute w-full h-full {{ $index === 0 ? 'block' : 'hidden' }}" data-slide="{{ $index }}">
                                @if(preg_match('/\.(mp4|webm|ogg)$/i', $image))
                                    <!-- Video -->
                                    <video class="w-full h-full object-cover" controls>
                                        <source src="{{ $image }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <!-- Image -->
                                    <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        @endforeach
                        
                        <!-- Fallback Image -->
                        @if(empty($images))
                            <div class="flex flex-col items-center justify-center w-full h-full text-gray-400">
                                <svg class="w-32 h-32" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 64 64">
                                    <circle cx="32" cy="32" r="10" stroke-width="2"/>
                                    <path stroke-width="2" d="M32 6v8M32 50v8M6 32h8M50 32h8M13.37 13.37l5.66 5.66M44.97 44.97l5.66 5.66M13.37 50.63l5.66-5.66M44.97 19.03l5.66-5.66"/>
                                    <circle cx="32" cy="32" r="5" fill="currentColor" opacity=".3"/>
                                </svg>
                                <span class="text-sm mt-3 font-medium">No Image Available</span>
                            </div>
                        @endif
                    </div>

                    <!-- Previous/Next Buttons (Show only if multiple images) -->
                    @if($hasMultiple)
                        <button onclick="changeSlide(-1)" class="absolute top-1/2 -translate-y-1/2 left-2 bg-black/50 hover:bg-black/75 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button onclick="changeSlide(1)" class="absolute top-1/2 -translate-y-1/2 right-2 bg-black/50 hover:bg-black/75 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        <!-- Slide Indicators (Dots) -->
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                            @foreach($images as $index => $image)
                                <button onclick="currentSlide({{ $index }})" class="dot w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all {{ $index === 0 ? 'bg-white w-6' : '' }}" data-dot="{{ $index }}"></button>
                            @endforeach
                        </div>

                        <!-- Slide Counter -->
                        <div class="absolute top-4 right-4 bg-black/60 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            <span id="current-slide">1</span> / <span id="total-slides">{{ count($images) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Gallery (Show only if multiple images) -->
                @if($hasMultiple)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($images as $index => $image)
                            <button onclick="currentSlide({{ $index }})" class="thumbnail-item rounded overflow-hidden border-2 border-transparent hover:border-blue-400 transition-all {{ $index === 0 ? 'border-blue-600' : '' }}" data-thumb="{{ $index }}">
                                @if(preg_match('/\.(mp4|webm|ogg)$/i', $image))
                                    <div class="w-full aspect-square bg-gray-300 flex items-center justify-center relative">
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"></path>
                                        </svg>
                                    </div>
                                @else
                                    <img src="{{ $image }}" alt="Thumbnail {{ $index + 1 }}" class="w-full aspect-square object-cover">
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Details -->
        <div class="lg:col-span-2">
            <!-- Header -->
            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
            <p class="text-gray-600 mb-4">SKU: {{ $product->sku }}</p>

            <!-- Rating -->
            <div class="flex items-center mb-4">
                <div class="flex text-yellow-400">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < round($product->average_rating ?? 0))
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-gray-600 ml-2">({{ $product->reviews_count ?? 0 }} reviews)</span>
            </div>

            <!-- Price & Stock -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <div class="text-3xl font-bold text-blue-600 mb-2">₹{{ number_format($product->price, 2) }}</div>
                <div class="mb-2">
                    @if($product->stock_qty > 0)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded">{{ $product->stock_qty }} in stock</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded">Out of Stock</span>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Description</h2>
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>

            <!-- Specifications -->
            @if($product->specifications)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">Specifications</h2>
                    <table class="w-full border">
                        @foreach((is_array($product->specifications) ? $product->specifications : json_decode($product->specifications, true) ?? []) as $key => $value)
                            <tr class="border-b">
                                <td class="px-4 py-2 font-semibold text-gray-700">{{ $key }}</td>
                                <td class="px-4 py-2 text-gray-600">{{ $value }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif

            <!-- Product Info -->
            <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                <div>
                    <p class="text-gray-600">Brand</p>
                    <p class="font-semibold"><a href="/products?brand_id={{ $product->brand->id }}" class="text-blue-600 hover:underline">{{ $product->brand->name }}</a></p>
                </div>
                <div>
                    <p class="text-gray-600">Category</p>
                    <p class="font-semibold"><a href="/products?category_id={{ $product->category->id }}" class="text-blue-600 hover:underline">{{ $product->category->name }}</a></p>
                </div>
                <div>
                    <p class="text-gray-600">Vessel Type</p>
                    <p class="font-semibold">{{ $product->vesselType->name ?? 'General' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Part Number</p>
                    <p class="font-semibold">{{ $product->part_number }}</p>
                </div>
            </div>

            <!-- Add to Cart Form -->
            <div class="flex gap-4">
                <form onsubmit="addToCart(event)" class="flex gap-4 flex-1">
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_qty }}" class="w-20 px-3 py-2 border rounded-lg" {{ $product->stock_qty === 0 ? 'disabled' : '' }} />
                    <button type="submit" {{ $product->stock_qty === 0 ? 'disabled' : '' }} class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                        Add to Cart
                    </button>
                </form>
                <button type="button" class="px-6 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50">
                    <i class="fas fa-heart"></i>
                </button>
            </div>

            <!-- Shipping Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-truck mr-2"></i>
                    Free shipping on orders above ₹5,000 | Delivery in 2-3 business days
                </p>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($relatedProducts as $related)
                    <a href="/products/{{ $related->id }}" class="bg-white rounded-lg shadow hover:shadow-lg transition">
                        <div class="bg-gray-200 h-40 rounded-t-lg overflow-hidden">
                            <img src="{{ (is_array($related->images) ? $related->images : json_decode($related->images, true) ?? [])[0] ?? 'https://via.placeholder.com/300' }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm truncate">{{ $related->name }}</h3>
                            <p class="text-lg font-bold text-blue-600">₹{{ number_format($related->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
// Slideshow functionality
let currentSlideIndex = 0;
let autoplayInterval = null;
const totalSlides = document.querySelectorAll('.slide').length;

// Initialize slideshow
function initSlideshow() {
    if (totalSlides > 1) {
        startAutoplay();
        
        // Pause autoplay on hover
        const container = document.getElementById('slideshow-container');
        if (container) {
            container.addEventListener('mouseenter', stopAutoplay);
            container.addEventListener('mouseleave', startAutoplay);
        }
    }
}

// Show specific slide
function currentSlide(index) {
    showSlide(index);
    if (autoplayInterval) {
        stopAutoplay();
        startAutoplay();
    }
}

// Navigate slides
function changeSlide(direction) {
    showSlide(currentSlideIndex + direction);
    if (autoplayInterval) {
        stopAutoplay();
        startAutoplay();
    }
}

// Display the slide at given index
function showSlide(index) {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    
    if (slides.length === 0) return;
    
    // Wrap around
    if (index >= slides.length) {
        currentSlideIndex = 0;
    } else if (index < 0) {
        currentSlideIndex = slides.length - 1;
    } else {
        currentSlideIndex = index;
    }
    
    // Hide all slides
    slides.forEach(slide => slide.classList.add('hidden'));
    
    // Show current slide
    slides[currentSlideIndex].classList.remove('hidden');
    
    // Update dots
    dots.forEach((dot, idx) => {
        if (idx === currentSlideIndex) {
            dot.classList.add('bg-white', 'w-6');
            dot.classList.remove('bg-white/50', 'w-2');
        } else {
            dot.classList.remove('bg-white', 'w-6');
            dot.classList.add('bg-white/50', 'w-2');
        }
    });
    
    // Update thumbnails
    thumbnails.forEach((thumb, idx) => {
        if (idx === currentSlideIndex) {
            thumb.classList.add('border-blue-600');
            thumb.classList.remove('border-transparent');
        } else {
            thumb.classList.remove('border-blue-600');
            thumb.classList.add('border-transparent');
        }
    });
    
    // Update counter
    const counterEl = document.getElementById('current-slide');
    if (counterEl) {
        counterEl.textContent = currentSlideIndex + 1;
    }
}

// Autoplay functionality
function startAutoplay() {
    if (totalSlides <= 1) return;
    
    autoplayInterval = setInterval(() => {
        showSlide(currentSlideIndex + 1);
    }, 5000); // Change slide every 5 seconds
}

function stopAutoplay() {
    if (autoplayInterval) {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
    }
}

// CSS for fade animation
const style = document.createElement('style');
style.textContent = `
    .slide {
        transition: opacity 0.5s ease-in-out;
    }
    .fade.hidden {
        opacity: 0;
        pointer-events: none;
    }
    .fade:not(.hidden) {
        opacity: 1;
    }
`;
document.head.appendChild(style);

// Add to Cart
async function addToCart(event) {
    event.preventDefault();
    const quantity = event.target.quantity.value;
    const productId = {{ $product->id }};
    const productUrl = window.location.href; // Current product URL
    
    try {
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Authorization': 'Bearer ' + (localStorage.getItem('token') || '')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(quantity)
            })
        });

        const data = await response.json();

        if (response.ok) {
            // User is logged in - add to cart and go to cart page
            showNotification('Product added to cart successfully!', 'success', 'Success');
            setTimeout(() => {
                window.location.href = data.redirect; // Redirect to cart page
            }, 1000);
        } else if (response.status === 401) {
            // User not logged in - store product info and redirect to login
            sessionStorage.setItem('pendingAddToCart', JSON.stringify({
                product_id: productId,
                quantity: parseInt(quantity),
                return_url: productUrl // Return to product page after login
            }));
            showNotification('Redirecting to login...', 'info', 'Please Login');
            setTimeout(() => {
                window.location.href = data.redirect + '?return_to=' + encodeURIComponent(productUrl);
            }, 1000);
        } else {
            showNotification(data.error || 'Error adding to cart', 'error', 'Error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error', 'Error');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initSlideshow);
</script>
@endsection
