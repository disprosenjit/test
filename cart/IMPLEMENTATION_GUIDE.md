# Implementation Guide - Ship Spare Parts Store

## 1. API ROUTES & ENDPOINTS

### Authentication Routes
```php
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/auth/refresh-token', [AuthController::class, 'refreshToken'])->middleware('auth:sanctum');
    Route::get('/auth/me', [AuthController::class, 'getProfile'])->middleware('auth:sanctum');
});
```

### Product Routes
```php
Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); // List all
    Route::get('/search', [ProductController::class, 'search']); // Full-text search
    Route::get('/{id}', [ProductController::class, 'show']); // Get single product
    Route::get('/{id}/related', [ProductController::class, 'relatedProducts']); // Related products
    Route::get('/filter/brands', [ProductController::class, 'getBrands']); // Get brands
    Route::get('/filter/categories', [ProductController::class, 'getCategories']); // Get categories
    Route::get('/filter/vessel-types', [ProductController::class, 'getVesselTypes']); // Get vessel types
});
```

### Cart Routes
```php
Route::middleware('auth:sanctum')->prefix('/cart')->group(function () {
    Route::get('/', [CartController::class, 'show']); // Get cart
    Route::post('/add', [CartController::class, 'add']); // Add product
    Route::put('/update/{item_id}', [CartController::class, 'update']); // Update quantity
    Route::delete('/remove/{item_id}', [CartController::class, 'remove']); // Remove product
    Route::delete('/clear', [CartController::class, 'clear']); // Clear cart
});

// Guest cart (via session)
Route::prefix('/guest-cart')->middleware('throttle:100,1')->group(function () {
    Route::post('/add', [GuestCartController::class, 'add']);
    Route::put('/update/{item_id}', [GuestCartController::class, 'update']);
    Route::delete('/remove/{item_id}', [GuestCartController::class, 'remove']);
    Route::get('/', [GuestCartController::class, 'show']);
});
```

### Order Routes
```php
Route::middleware('auth:sanctum')->prefix('/orders')->group(function () {
    Route::post('/', [OrderController::class, 'create']); // Create order
    Route::get('/', [OrderController::class, 'index']); // List user orders
    Route::get('/{id}', [OrderController::class, 'show']); // Get order details
    Route::get('/{id}/status', [OrderController::class, 'trackStatus']); // Track status
    Route::put('/{id}/cancel', [OrderController::class, 'cancel']); // Cancel order
    Route::get('/{id}/invoice', [OrderController::class, 'getInvoice']); // Download invoice
});
```

### Payment Routes
```php
Route::middleware('auth:sanctum')->prefix('/payments')->group(function () {
    Route::post('/bank-transfer/{order_id}', [PaymentController::class, 'initiateBankTransfer']);
    Route::post('/upi/{order_id}', [PaymentController::class, 'initiateUPI']);
    Route::post('/card/{order_id}', [PaymentController::class, 'initiateCard']);
    Route::post('/verify-bank-transfer', [PaymentController::class, 'verifyBankTransfer']);
    Route::get('/{order_id}/status', [PaymentController::class, 'getPaymentStatus']);
});

// Webhook for payment gateway
Route::post('/webhooks/payment', [PaymentController::class, 'webhookHandler']);
```

### Chatbot Routes
```php
Route::prefix('/chatbot')->middleware('throttle:30,1')->group(function () {
    Route::get('/faqs', [ChatbotController::class, 'getFAQs']); // Get all FAQs
    Route::get('/faqs/search', [ChatbotController::class, 'searchFAQs']); // Search FAQs
    Route::post('/message', [ChatbotController::class, 'sendMessage']); // Send message
    Route::get('/history', [ChatbotController::class, 'getConversationHistory']); // Get history
    Route::post('/escalate', [ChatbotController::class, 'escalateToSupport']); // Escalate
});
```

### Address Routes
```php
Route::middleware('auth:sanctum')->prefix('/addresses')->group(function () {
    Route::get('/', [AddressController::class, 'index']); // List addresses
    Route::post('/', [AddressController::class, 'store']); // Add address
    Route::put('/{id}', [AddressController::class, 'update']); // Update address
    Route::delete('/{id}', [AddressController::class, 'destroy']); // Delete address
    Route::put('/{id}/set-default/{type}', [AddressController::class, 'setDefault']); // Set default
});
```

### Admin Routes
```php
Route::middleware(['auth:sanctum', 'admin'])->prefix('/admin')->group(function () {
    // Products
    Route::post('/products', [AdminProductController::class, 'store']);
    Route::put('/products/{id}', [AdminProductController::class, 'update']);
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy']);
    Route::post('/products/bulk-upload', [AdminProductController::class, 'bulkUpload']);
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
    Route::post('/orders/{id}/shipment', [AdminOrderController::class, 'createShipment']);
    
    // Payments
    Route::get('/payments/pending', [AdminPaymentController::class, 'getPending']);
    Route::put('/payments/{id}/approve', [AdminPaymentController::class, 'approve']);
    Route::put('/payments/{id}/reject', [AdminPaymentController::class, 'reject']);
    
    // Inventory
    Route::get('/inventory', [AdminInventoryController::class, 'index']);
    Route::put('/inventory/{id}', [AdminInventoryController::class, 'update']);
    Route::post('/inventory/low-stock', [AdminInventoryController::class, 'getLowStock']);
    
    // Analytics
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    Route::get('/analytics/sales', [AdminDashboardController::class, 'getSalesAnalytics']);
    Route::get('/analytics/products', [AdminDashboardController::class, 'getProductAnalytics']);
    
    // FAQs
    Route::resource('/faqs', AdminChatbotController::class);
});
```

---

## 2. KEY CONTROLLERS

### ProductController
```php
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = new ProductSearchService();
        
        $search->applyFilters([
            'search' => $request->get('q'),
            'brand_id' => $request->get('brand_id'),
            'category_id' => $request->get('category_id'),
            'vessel_type_id' => $request->get('vessel_type_id'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'in_stock' => $request->boolean('in_stock', false),
        ]);

        $search->sortBy($request->get('sort_by', 'relevance'));
        $products = $search->paginate($request->get('per_page', 24));

        return response()->json($products);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $search = new ProductSearchService();
        $results = $search->search($validated['q'])->paginate();

        return response()->json($results);
    }

    public function show(int $id)
    {
        $product = Product::with(['brand', 'category', 'vesselType'])
            ->findOrFail($id);

        // Increment view count asynchronously
        $product->incrementViewCount();

        return response()->json($product);
    }

    public function relatedProducts(int $id)
    {
        $product = Product::findOrFail($id);

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->limit(8)
            ->get();

        return response()->json($related);
    }

    public function getBrands()
    {
        $brands = Brand::active()->orderBy('name')->get();
        return response()->json($brands);
    }

    public function getCategories()
    {
        $categories = Category::active()
            ->with('subcategories')
            ->whereNull('parent_id')
            ->orderBy('display_order')
            ->get();
        return response()->json($categories);
    }

    public function getVesselTypes()
    {
        $types = VesselType::active()->orderBy('name')->get();
        return response()->json($types);
    }
}
```

### CartController
```php
class CartController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $cartService = new CartService($user, $request->session()->get('session_id'));
        
        return response()->json($cartService->getSummary());
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        try {
            $user = $request->user();
            $cartService = new CartService($user);
            $cartService->addProduct($validated['product_id'], $validated['quantity']);

            return response()->json([
                'message' => 'Product added to cart',
                'cart' => $cartService->getSummary(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, int $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        try {
            $user = $request->user();
            $cartService = new CartService($user);
            $cartService->cart->updateItem($itemId, $validated['quantity']);

            return response()->json($cartService->getSummary());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function remove(Request $request, int $itemId)
    {
        $user = $request->user();
        $cartService = new CartService($user);
        $cartService->cart->removeItem($itemId);

        return response()->json($cartService->getSummary());
    }

    public function clear(Request $request)
    {
        $user = $request->user();
        $cartService = new CartService($user);
        $cartService->clear();

        return response()->json(['message' => 'Cart cleared']);
    }
}
```

---

## 3. REQUEST/RESPONSE VALIDATION

### Create Order Request Validation
```php
$request->validate([
    'shipping_address_id' => 'required|exists:addresses,id',
    'billing_address_id' => 'required|exists:addresses,id',
    'payment_method' => 'required|in:bank_transfer,upi,credit_card,debit_card',
    'notes' => 'nullable|string|max:500',
]);
```

### Product Search Filters
```php
$request->validate([
    'q' => 'nullable|string|max:100',
    'brand_id' => 'nullable|integer|exists:brands,id',
    'category_id' => 'nullable|integer|exists:categories,id',
    'vessel_type_id' => 'nullable|integer|exists:vessel_types,id',
    'min_price' => 'nullable|numeric|min:0',
    'max_price' => 'nullable|numeric|min:0',
    'in_stock' => 'nullable|boolean',
    'sort_by' => 'nullable|in:relevance,price_asc,price_desc,newest,popular,rating',
    'per_page' => 'nullable|integer|min:1|max:100',
    'page' => 'nullable|integer|min:1',
]);
```

---

## 4. RESPONSE EXAMPLES

### Product List Response
```json
{
    "data": [
        {
            "id": 1,
            "sku": "PUMP-001",
            "part_number": "P-123456",
            "name": "Marine Pump Assembly",
            "brand": {
                "id": 1,
                "name": "Grundfos"
            },
            "category": {
                "id": 2,
                "name": "Pumps"
            },
            "price": 5000.00,
            "stock_qty": 25,
            "rating": 4.5,
            "image_url": "https://cdn.example.com/pump-001.jpg",
            "is_active": true
        }
    ],
    "pagination": {
        "total": 150,
        "per_page": 24,
        "current_page": 1,
        "last_page": 7,
        "from": 1,
        "to": 24,
        "links": []
    }
}
```

### Cart Response
```json
{
    "item_count": 3,
    "subtotal": 15000.00,
    "tax": 2700.00,
    "total": 17700.00,
    "items": [
        {
            "id": 1,
            "product_id": 1,
            "product": {
                "id": 1,
                "name": "Marine Pump Assembly",
                "sku": "PUMP-001"
            },
            "quantity": 2,
            "price": 5000.00,
            "subtotal": 10000.00
        }
    ]
}
```

### Order Response
```json
{
    "order_id": 1,
    "order_number": "ORD20260425000001",
    "status": "pending",
    "payment_status": "pending",
    "payment_method": "bank_transfer",
    "subtotal": 15000.00,
    "tax": 2700.00,
    "total": 17700.00,
    "items": [
        {
            "product_name": "Marine Pump Assembly",
            "sku": "PUMP-001",
            "quantity": 2,
            "unit_price": 5000.00,
            "subtotal": 10000.00
        }
    ],
    "created_at": "2026-04-25T10:30:00Z",
    "updated_at": "2026-04-25T10:30:00Z"
}
```

---

## 5. CACHING & PERFORMANCE OPTIMIZATION

### Redis Cache Keys
```
product::{id} - 1 hour
category::{id} - 24 hours
search_results::{hash} - 30 minutes
cart::{user_id} - 7 days
inventory::{product_id} - 30 minutes
faq::{id} - 24 hours
```

### Cache Implementation
```php
class ProductController extends Controller
{
    public function show(int $id)
    {
        $product = Cache::remember("product::{$id}", 3600, function () use ($id) {
            return Product::with(['brand', 'category', 'vesselType'])
                ->findOrFail($id);
        });

        $product->incrementViewCount();
        return response()->json($product);
    }
}
```

---

## 6. QUEUE JOBS

### Send Order Confirmation Email
```php
class SendOrderConfirmationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Order $order) {}

    public function handle()
    {
        Mail::to($this->order->user->email)
            ->send(new OrderConfirmation($this->order));

        // Log in audit
        AuditLog::create([
            'action' => 'order_confirmation_sent',
            'model_type' => 'Order',
            'model_id' => $this->order->id,
        ]);
    }
}
```

### Update Product Search Index
```php
class IndexProductSearch implements ShouldQueue
{
    public function __construct(protected Product $product) {}

    public function handle()
    {
        // If using Elasticsearch/Meilisearch
        // $this->product->syncToSearch();
        
        // Or update custom search indexes
        Cache::forget("search_results::*");
    }
}
```

---

## 7. EVENT LISTENERS

### OrderCreated Event
```php
// Triggers:
// - Send confirmation email (queue)
// - Update inventory (synchronous)
// - Log audit
// - Notify admins
```

### PaymentApproved Event
```php
// Triggers:
// - Update order status
// - Send payment receipt
// - Update accounting
// - Release inventory if pending
```

---

## 8. REQUIRED PACKAGES

```bash
composer require laravel/sanctum          # API Authentication
composer require laravel/scout             # Search integration
composer require meilisearch/meilisearch-php  # Search engine
composer require intervention/image        # Image manipulation
composer require maatwebsite/excel         # CSV bulk upload
composer require stripe/stripe-php         # Payment gateway (optional)
composer require guzzlehttp/guzzle        # HTTP client for APIs
```

---

## 9. CONFIGURATION FILES

### .env Settings
```
APP_NAME="Ship Spare Parts Store"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://shipparts.example.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ship_spare_parts
DB_USERNAME=app_user
DB_PASSWORD=secure_password

REDIS_HOST=localhost
REDIS_PORT=6379

MEILISEARCH_HOST=http://localhost:7700
MEILISEARCH_KEY=admin_key

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=noreply@example.com
MAIL_PASSWORD=password

SUPPORT_EMAIL=support@shipparts.example.com
```

---

## 10. MIDDLEWARE

### Admin Middleware
```php
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->isAdmin()) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
```

### Rate Limiting
```php
// routes/api.php
RateLimiter::for('api', function (Request $request) {
    return $request->user()?->id
        ? Limit::perMinute(100)->by($request->user()->id)
        : Limit::perMinute(20)->by($request->ip());
});
```

---

This implementation guide covers the essential API structure and coding patterns for the Ship Spare Parts Store.
