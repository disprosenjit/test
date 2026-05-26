# FRONTEND & UX IMPLEMENTATION GUIDE

## 1. RESPONSIVE LAYOUT - Mobile First Approach

### Base TailwindCSS Configuration
```jsx
// resources/css/app.css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer components {
    .btn-primary {
        @apply px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition;
    }

    .card {
        @apply bg-white rounded-lg shadow-md p-4;
    }

    .container-responsive {
        @apply w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
    }
}
```

---

## 2. PRODUCT LISTING PAGE

### Vue Component Structure
```vue
<template>
    <div class="container-responsive py-8">
        <!-- Header -->
        <h1 class="text-3xl font-bold mb-8">Spare Parts Catalog</h1>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <aside class="lg:col-span-1">
                <div class="card">
                    <h3 class="text-lg font-semibold mb-4">Filters</h3>

                    <!-- Search -->
                    <div class="mb-4">
                        <input
                            v-model="filters.search"
                            type="text"
                            placeholder="Search part number..."
                            class="w-full px-3 py-2 border rounded-lg"
                            @input="applyFilters"
                        />
                    </div>

                    <!-- Brand Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Brand</label>
                        <select
                            v-model="filters.brand_id"
                            class="w-full px-3 py-2 border rounded-lg"
                            @change="applyFilters"
                        >
                            <option value="">All Brands</option>
                            <option
                                v-for="brand in brands"
                                :key="brand.id"
                                :value="brand.id"
                            >
                                {{ brand.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Price Range</label>
                        <div class="space-y-2">
                            <input
                                v-model.number="filters.min_price"
                                type="number"
                                placeholder="Min"
                                class="w-full px-3 py-2 border rounded-lg text-sm"
                                @input="applyFilters"
                            />
                            <input
                                v-model.number="filters.max_price"
                                type="number"
                                placeholder="Max"
                                class="w-full px-3 py-2 border rounded-lg text-sm"
                                @input="applyFilters"
                            />
                        </div>
                    </div>

                    <!-- In Stock Filter -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input
                                v-model="filters.in_stock"
                                type="checkbox"
                                class="mr-2"
                                @change="applyFilters"
                            />
                            <span class="text-sm">In Stock Only</span>
                        </label>
                    </div>

                    <!-- Sorting -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Sort By</label>
                        <select
                            v-model="filters.sort_by"
                            class="w-full px-3 py-2 border rounded-lg"
                            @change="applyFilters"
                        >
                            <option value="relevance">Relevance</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="newest">Newest</option>
                            <option value="popular">Most Popular</option>
                        </select>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <main class="lg:col-span-3">
                <!-- Loading State -->
                <div v-if="loading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>

                <!-- Products -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="card hover:shadow-lg transition cursor-pointer"
                        @click="goToProduct(product.id)"
                    >
                        <!-- Image -->
                        <div class="mb-3 overflow-hidden rounded bg-gray-100 h-48">
                            <img
                                :src="product.image_url"
                                :alt="product.name"
                                class="w-full h-full object-cover hover:scale-105 transition"
                            />
                        </div>

                        <!-- Info -->
                        <h3 class="font-semibold text-sm mb-1 truncate">
                            {{ product.name }}
                        </h3>
                        <p class="text-xs text-gray-600 mb-2">SKU: {{ product.sku }}</p>

                        <!-- Rating -->
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <span v-for="i in 5" :key="i">
                                    <i :class="i <= Math.round(product.rating) ? 'fas fa-star' : 'far fa-star'"></i>
                                </span>
                            </div>
                            <span class="text-xs text-gray-600 ml-1">({{ product.rating }})</span>
                        </div>

                        <!-- Price & Stock -->
                        <div class="flex justify-between items-end mb-3">
                            <span class="text-lg font-bold text-blue-600">
                                ₹{{ product.price.toFixed(2) }}
                            </span>
                            <span
                                :class="{
                                    'text-xs px-2 py-1 rounded': true,
                                    'bg-green-100 text-green-800': product.stock_qty > 0,
                                    'bg-red-100 text-red-800': product.stock_qty === 0,
                                }"
                            >
                                {{ product.stock_qty > 0 ? 'In Stock' : 'Out of Stock' }}
                            </span>
                        </div>

                        <!-- Add to Cart Button -->
                        <button
                            @click.stop="addToCart(product.id)"
                            :disabled="product.stock_qty === 0 || addingToCart"
                            class="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ addingToCart ? 'Adding...' : 'Add to Cart' }}
                        </button>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="!loading && products.length > 0" class="mt-8 flex justify-center gap-2">
                    <button
                        v-if="currentPage > 1"
                        @click="previousPage"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100"
                    >
                        Previous
                    </button>
                    <span class="px-4 py-2">Page {{ currentPage }} of {{ totalPages }}</span>
                    <button
                        v-if="currentPage < totalPages"
                        @click="nextPage"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100"
                    >
                        Next
                    </button>
                </div>

                <!-- Empty State -->
                <div v-if="!loading && products.length === 0" class="text-center py-12">
                    <p class="text-gray-600">No products found. Try adjusting your filters.</p>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const loading = ref(false);
const addingToCart = ref(false);
const products = ref([]);
const brands = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);

const filters = reactive({
    search: '',
    brand_id: '',
    category_id: '',
    vessel_type_id: '',
    min_price: '',
    max_price: '',
    in_stock: false,
    sort_by: 'relevance',
    per_page: 24,
});

onMounted(() => {
    fetchBrands();
    applyFilters();
});

async function fetchBrands() {
    try {
        const response = await fetch('/api/products/filter/brands');
        brands.value = await response.json();
    } catch (error) {
        console.error('Error fetching brands:', error);
    }
}

async function applyFilters() {
    loading.value = true;
    try {
        const params = new URLSearchParams();
        Object.entries(filters).forEach(([key, value]) => {
            if (value !== '' && value !== false) {
                params.append(key, value);
            }
        });
        params.append('page', currentPage.value);

        const response = await fetch(`/api/products?${params}`);
        const data = await response.json();
        products.value = data.data;
        currentPage.value = data.current_page;
        totalPages.value = data.last_page;
    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        loading.value = false;
    }
}

async function addToCart(productId) {
    addingToCart.value = true;
    try {
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1,
            }),
        });

        if (response.ok) {
            alert('Product added to cart!');
        } else {
            alert('Error adding to cart');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
    } finally {
        addingToCart.value = false;
    }
}

function goToProduct(productId) {
    router.push(`/products/${productId}`);
}

function previousPage() {
    if (currentPage.value > 1) {
        currentPage.value--;
        applyFilters();
    }
}

function nextPage() {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
        applyFilters();
    }
}
</script>
```

---

## 3. CHECKOUT FLOW

### Step 1: Shipping Address
```vue
<template>
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Shipping Address</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Full Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg"
                />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input
                    v-model="form.phone"
                    type="tel"
                    class="w-full px-3 py-2 border rounded-lg"
                />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Address</label>
                <input
                    v-model="form.address_line_1"
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg"
                />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Apartment, Suite, etc. (Optional)</label>
                <input
                    v-model="form.address_line_2"
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg"
                />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">City</label>
                <input
                    v-model="form.city"
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg"
                />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">State</label>
                <input
                    v-model="form.state"
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg"
                />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Postal Code</label>
                <input
                    v-model="form.postal_code"
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg"
                />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Country</label>
                <input
                    v-model="form.country"
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg"
                    value="India"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';

const form = reactive({
    name: '',
    phone: '',
    company_name: '',
    address_line_1: '',
    address_line_2: '',
    city: '',
    state: '',
    postal_code: '',
    country: 'India',
});
</script>
```

### Step 2: Payment Method Selection
```vue
<template>
    <div class="card">
        <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
        <div class="space-y-3">
            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" :class="{'border-blue-500 bg-blue-50': paymentMethod === 'bank_transfer'}">
                <input
                    type="radio"
                    value="bank_transfer"
                    v-model="paymentMethod"
                    class="mr-3"
                />
                <div>
                    <div class="font-semibold">Bank Transfer</div>
                    <div class="text-sm text-gray-600">Transfer funds directly to our bank account</div>
                </div>
            </label>

            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" :class="{'border-blue-500 bg-blue-50': paymentMethod === 'upi'}">
                <input
                    type="radio"
                    value="upi"
                    v-model="paymentMethod"
                    class="mr-3"
                />
                <div>
                    <div class="font-semibold">UPI Payment</div>
                    <div class="text-sm text-gray-600">Pay instantly via UPI or QR code</div>
                </div>
            </label>

            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" :class="{'border-blue-500 bg-blue-50': paymentMethod === 'card'}">
                <input
                    type="radio"
                    value="card"
                    v-model="paymentMethod"
                    class="mr-3"
                />
                <div>
                    <div class="font-semibold">Credit/Debit Card</div>
                    <div class="text-sm text-gray-600">Secure payment via credit or debit card</div>
                </div>
            </label>
        </div>

        <!-- Bank Transfer Details -->
        <div v-if="paymentMethod === 'bank_transfer'" class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h3 class="font-semibold mb-2">Bank Account Details</h3>
            <p><strong>Account Holder:</strong> Ship Spare Parts Store</p>
            <p><strong>Bank:</strong> HDFC Bank</p>
            <p><strong>Account Number:</strong> 1234567890123456</p>
            <p><strong>IFSC Code:</strong> HDFC0001234</p>
        </div>

        <!-- UPI QR Code -->
        <div v-if="paymentMethod === 'upi'" class="mt-4 text-center">
            <img src="/images/upi-qr.png" alt="UPI QR Code" class="w-32 h-32 mx-auto mb-2" />
            <p class="text-sm text-gray-600">Scan to pay instantly</p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const paymentMethod = ref('bank_transfer');
</script>
```

---

## 4. IMAGE OPTIMIZATION

### Lazy Loading & WebP Format
```vue
<template>
    <img
        loading="lazy"
        :src="product.image_url"
        :srcset="`
            ${product.image_url}?w=300&q=80 300w,
            ${product.image_url}?w=600&q=80 600w,
            ${product.image_url}?w=1200&q=80 1200w
        `"
        sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
        alt="Product"
        class="w-full h-auto"
    />
</template>
```

### Laravel Image Optimization
```php
// In controller
$product->image_url = $product->image_url . '?w=800&q=85&auto=format';
```

---

## 5. CHATBOT WIDGET

```vue
<template>
    <div class="fixed bottom-4 right-4 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50">
        <!-- Header -->
        <div class="bg-blue-600 text-white p-4 flex justify-between items-center">
            <span class="font-semibold">Support Chat</span>
            <button @click="toggle" class="hover:bg-blue-700 p-1 rounded">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Chat Messages -->
        <div v-if="isOpen" class="h-96 overflow-y-auto p-4 bg-gray-50 space-y-3">
            <div
                v-for="msg in messages"
                :key="msg.id"
                :class="{
                    'flex justify-end': msg.role === 'user',
                    'flex justify-start': msg.role === 'bot',
                }"
            >
                <div
                    :class="{
                        'bg-blue-600 text-white': msg.role === 'user',
                        'bg-gray-200 text-gray-800': msg.role === 'bot',
                        'rounded-lg px-4 py-2 max-w-xs': true,
                    }"
                >
                    {{ msg.content }}
                </div>
            </div>
        </div>

        <!-- Input -->
        <div v-if="isOpen" class="p-4 border-t">
            <div class="flex gap-2">
                <input
                    v-model="userMessage"
                    @keyup.enter="sendMessage"
                    type="text"
                    placeholder="Type a message..."
                    class="flex-1 px-3 py-2 border rounded-lg text-sm"
                />
                <button
                    @click="sendMessage"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                >
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <!-- Minimize Button -->
        <button
            v-if="!isOpen"
            @click="toggle"
            class="w-full bg-blue-600 text-white p-4 hover:bg-blue-700"
        >
            💬 Chat with us!
        </button>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const isOpen = ref(false);
const userMessage = ref('');
const messages = ref([]);

function toggle() {
    isOpen.value = !isOpen.value;
}

async function sendMessage() {
    if (!userMessage.value.trim()) return;

    messages.value.push({
        id: Date.now(),
        role: 'user',
        content: userMessage.value,
    });

    try {
        const response = await fetch('/api/chatbot/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                message: userMessage.value,
            }),
        });

        const data = await response.json();
        messages.value.push({
            id: Date.now(),
            role: 'bot',
            content: data.response,
        });

        userMessage.value = '';
    } catch (error) {
        console.error('Error sending message:', error);
    }
}
</script>
```

---

## 6. SEO OPTIMIZATION

### Meta Tags & Schema Markup
```blade
<!-- Product Page -->
<meta name="description" content="{{ $product->description }}">
<meta name="keywords" content="{{ $product->part_number }}, {{ $product->brand->name }}">
<meta property="og:title" content="{{ $product->name }}">
<meta property="og:description" content="{{ $product->description }}">
<meta property="og:image" content="{{ $product->image_url }}">

<!-- Schema Markup for Product -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->name }}",
  "image": "{{ $product->image_url }}",
  "description": "{{ $product->description }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $product->brand->name }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ request()->url() }}",
    "priceCurrency": "INR",
    "price": "{{ $product->price }}",
    "availability": "{{ $product->stock_qty > 0 ? 'InStock' : 'OutOfStock' }}"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $product->rating }}",
    "reviewCount": "{{ $product->reviews_count }}"
  }
}
</script>
```

---

## 7. PERFORMANCE METRICS

### Target Performance Scores
```
Lighthouse Scores:
- Performance: > 90
- Accessibility: > 95
- Best Practices: > 95
- SEO: > 95

Core Web Vitals:
- LCP (Largest Contentful Paint): < 2.5s
- FID (First Input Delay): < 100ms
- CLS (Cumulative Layout Shift): < 0.1
```

---

## 8. RESPONSIVE BREAKPOINTS

```scss
// Mobile First
$mobile: 320px;      // Default
$tablet: 768px;      // md
$desktop: 1024px;    // lg
$widescreen: 1280px; // xl
$ultrawide: 1536px;  // 2xl

// Usage
@media (min-width: $tablet) {
    // Tablet styles
}

@media (min-width: $desktop) {
    // Desktop styles
}
```

---

This frontend guide provides production-ready component examples with full responsive design, optimized performance, and SEO best practices.
