<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\AddressController;

// Public Routes
Route::middleware('throttle:60,1')->group(function () {
    // Authentication
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
});

// Product Routes (Public)
Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/search', [ProductController::class, 'search']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('/{id}/related', [ProductController::class, 'relatedProducts']);
    Route::get('/filter/brands', [ProductController::class, 'getBrands']);
    Route::get('/filter/categories', [ProductController::class, 'getCategories']);
    Route::get('/filter/vessel-types', [ProductController::class, 'getVesselTypes']);
});

// Chatbot Routes (Public)
Route::prefix('/chatbot')->middleware('throttle:30,1')->group(function () {
    Route::get('/faqs', [ChatbotController::class, 'getFAQs']);
    Route::get('/faqs/search', [ChatbotController::class, 'searchFAQs']);
    Route::post('/message', [ChatbotController::class, 'sendMessage']);
});

// Cart Routes (Session-based auth for web users, JSON-friendly)
// CSRF is excluded in bootstrap/app.php for these routes
Route::prefix('/cart')->middleware('web')->group(function () {
    // Cart Routes - Uses session for web users
    Route::get('/', [CartController::class, 'show']);
    Route::post('/add', [CartController::class, 'add']);
    Route::put('/update/{item_id}', [CartController::class, 'update']);
    Route::delete('/remove/{item_id}', [CartController::class, 'remove']);
    Route::delete('/clear', [CartController::class, 'clear']);
});

// Order Routes (Session-based auth for web checkout)
Route::prefix('/orders')->middleware(['web', 'auth'])->group(function () {
    Route::post('/', [OrderController::class, 'create']);
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::get('/{id}/status', [OrderController::class, 'trackStatus']);
    Route::put('/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('/{id}/invoice', [OrderController::class, 'getInvoice']);
});

// Address Routes (Session-based auth for web checkout)
Route::prefix('/addresses')->middleware(['web', 'auth'])->group(function () {
    Route::get('/', [AddressController::class, 'index']);
    Route::get('/{id}', [AddressController::class, 'show']);
    Route::post('/', [AddressController::class, 'store']);
    Route::put('/{id}', [AddressController::class, 'update']);
    Route::delete('/{id}', [AddressController::class, 'destroy']);
    Route::put('/{id}/set-default/{type}', [AddressController::class, 'setDefault']);
});

// Payment Routes (Session-based auth for web checkout)
Route::prefix('/payments')->middleware(['web', 'auth'])->group(function () {
    Route::post('/bank-transfer/{order_id}', [PaymentController::class, 'initiateBankTransfer']);
    Route::post('/upi/{order_id}', [PaymentController::class, 'initiateUPI']);
    Route::post('/card/{order_id}', [PaymentController::class, 'initiateCard']);
    Route::post('/stripe/{order_id}', [PaymentController::class, 'initiateStripe']);
    Route::post('/paypal/{order_id}', [PaymentController::class, 'initiatePaypal']);
    Route::post('/verify-bank-transfer', [PaymentController::class, 'verifyBankTransfer']);
    Route::get('/{order_id}/status', [PaymentController::class, 'getPaymentStatus']);
});

// Token-Based Authenticated Routes (API clients with tokens)
Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh-token', [AuthController::class, 'refreshToken']);
    Route::get('/auth/me', [AuthController::class, 'getProfile']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);

    // Chatbot Routes (Authenticated)
    Route::prefix('/chatbot')->middleware('throttle:30,1')->group(function () {
        Route::get('/history', [ChatbotController::class, 'getConversationHistory']);
        Route::post('/escalate', [ChatbotController::class, 'escalateToSupport']);
    });
});

// Webhook Routes (No authentication)
Route::post('/webhooks/payment', [PaymentController::class, 'webhookHandler']);

// Health Check
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
