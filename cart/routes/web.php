<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\StripePaymentController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\FaqController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminInventoryController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminChatbotFaqController;
use App\Http\Controllers\Webhook\PayPalIpnController;
use App\Http\Controllers\Admin\AdminPayPalIpnController;

// ========================
// FRONTEND ROUTES
// ========================

// Home
Route::get('/', function () {
    $products = \App\Models\Commerce\Product::active()->with(['brand', 'category', 'vesselType', 'inventory'])->paginate(12);
    return view('welcome', compact('products'));
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/profile/billing-address', [AuthController::class, 'saveBillingAddress'])->name('profile.save-billing-address');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
});

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

// Stripe Payment Routes
Route::middleware('auth')->prefix('checkout/stripe')->name('checkout.stripe')->group(function () {
    Route::get('/card-form', [StripePaymentController::class, 'showCardForm'])->name('-card-form');
    Route::post('/process', [StripePaymentController::class, 'processPayment'])->name('-process');
    Route::get('/confirmation', [StripePaymentController::class, 'confirmPayment'])->name('-confirmation');
    Route::delete('/cards/{card}', [StripePaymentController::class, 'deleteCard'])->name('-delete-card');
});

// Alternative route names without stripe prefix
Route::middleware('auth')->group(function () {
    Route::get('/checkout/stripe/card-form', [StripePaymentController::class, 'showCardForm'])->name('checkout.stripe-card-form');
    Route::post('/checkout/stripe/process', [StripePaymentController::class, 'processPayment'])->name('checkout.stripe-process');
    Route::get('/checkout/stripe/confirmation', [StripePaymentController::class, 'confirmPayment'])->name('checkout.stripe-confirmation');
});

// Payment routes are loaded from payment plugins (Stripe, PayPal, Bank Transfer)

// Pages
Route::get('/pages/{page}', [PageController::class, 'show'])->name('pages.show');

// FAQs
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
Route::get('/faqs/{faq}', [FaqController::class, 'show'])->name('faqs.show');

// PayPal IPN Webhook (must be public for PayPal to call)
Route::post('/webhook/paypal/ipn', [\App\Http\Controllers\Webhook\PayPalIpnController::class, 'handle'])->name('webhook.paypal.ipn');

// ========================
// ADMIN ROUTES
// ========================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    // Products
    Route::get('/products/bulk-upload', [AdminProductController::class, 'bulkUploadForm'])->name('products.bulk-upload');
    Route::post('/products/bulk-upload', [AdminProductController::class, 'bulkUpload'])->name('products.bulk-upload-store');
    Route::resource('products', AdminProductController::class);

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/shipment', [AdminOrderController::class, 'createShipment'])->name('orders.shipment');
    Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');

    // Payments
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/stripe', [AdminPaymentController::class, 'stripe'])->name('payments.stripe');
    Route::get('/payments/paypal', [AdminPaymentController::class, 'paypal'])->name('payments.paypal');
    Route::get('/payments/paypal-ipn', [AdminPayPalIpnController::class, 'index'])->name('payments.paypal_ipn');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/verify-bank', [AdminPaymentController::class, 'verifyBank'])->name('payments.verifyBank');
    Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    Route::get('/payments/{payment}/proof', [AdminPaymentController::class, 'viewProof'])->name('payments.proof');
    Route::get('/payments/report', [AdminPaymentController::class, 'report'])->name('payments.report');

    // Payment Methods Configuration
    Route::get('/payment-methods', [\App\Http\Controllers\Admin\AdminPaymentMethodsController::class, 'index'])->name('payment_methods.index');
    Route::get('/payment-methods/{paymentMethod}', [\App\Http\Controllers\Admin\AdminPaymentMethodsController::class, 'show'])->name('payment_methods.show');
    Route::post('/payment-methods/{paymentMethod}/toggle', [\App\Http\Controllers\Admin\AdminPaymentMethodsController::class, 'toggle'])->name('payment_methods.toggle');
    Route::post('/payment-methods/{paymentMethod}/default', [\App\Http\Controllers\Admin\AdminPaymentMethodsController::class, 'setDefault'])->name('payment_methods.default');
    Route::put('/payment-methods/{paymentMethod}', [\App\Http\Controllers\Admin\AdminPaymentMethodsController::class, 'updateSettings'])->name('payment_methods.update');
    Route::post('/payment-methods/reorder', [\App\Http\Controllers\Admin\AdminPaymentMethodsController::class, 'reorder'])->name('payment_methods.reorder');

    // PayPal Settings
    Route::get('/payments/paypal-settings', [AdminPaymentController::class, 'paypalSettings'])->name('payments.paypal.settings');
    Route::post('/payments/paypal-settings', [AdminPaymentController::class, 'updatePaypalSettings'])->name('payments.paypal.settings.update');

    // PayPal IPN Management
    Route::get('/paypal-ipn', [AdminPayPalIpnController::class, 'index'])->name('paypal_ipn.index');
    Route::get('/paypal-ipn/dashboard', [AdminPayPalIpnController::class, 'dashboard'])->name('paypal_ipn.dashboard');
    Route::get('/paypal-ipn/settings', [AdminPayPalIpnController::class, 'settings'])->name('paypal_ipn.settings');
    Route::get('/paypal-ipn/{ipnLog}', [AdminPayPalIpnController::class, 'show'])->name('paypal_ipn.show');
    Route::post('/paypal-ipn/{ipnLog}/retry', [AdminPayPalIpnController::class, 'retry'])->name('paypal_ipn.retry');
    Route::post('/paypal-ipn/{ipnLog}/verify-manually', [AdminPayPalIpnController::class, 'verifyManually'])->name('paypal_ipn.verify');
    Route::post('/paypal-ipn/{ipnLog}/invalid', [AdminPayPalIpnController::class, 'markInvalid'])->name('paypal_ipn.invalid');
    Route::get('/paypal-ipn/{ipnLog}/raw', [AdminPayPalIpnController::class, 'viewRaw'])->name('paypal_ipn.raw');

    // Stripe Payment Management
    Route::prefix('payments/stripe')->name('payments.stripe.')->group(function () {
        // Stripe Settings
        Route::get('/settings', [AdminPaymentController::class, 'stripeSettings'])->name('settings');
        Route::post('/settings', [AdminPaymentController::class, 'updateStripeSettings'])->name('settings.update');
        Route::get('/config-verify', [AdminPaymentController::class, 'verifyStripeConfig'])->name('verify');

        // Stripe Refunds
        Route::get('/refunds', [AdminPaymentController::class, 'stripeRefunds'])->name('refunds');
        Route::get('/refunds/{refund}', [AdminPaymentController::class, 'showRefund'])->name('refunds.show');

        // Stripe Statistics
        Route::get('/stats', [AdminPaymentController::class, 'stripeStats'])->name('stats');
        Route::get('/export', [AdminPaymentController::class, 'exportStripeReport'])->name('export');
    });

    // Stripe Payment Processing (for specific payments)
    Route::prefix('payments/{payment}/stripe')->name('payments.stripe.')->group(function () {
        Route::post('/accept', [AdminPaymentController::class, 'acceptPayment'])->name('accept');
        Route::post('/reject', [AdminPaymentController::class, 'rejectPayment'])->name('reject');
        Route::post('/refund-full', [AdminPaymentController::class, 'refundFull'])->name('refund-full');
        Route::post('/refund-partial', [AdminPaymentController::class, 'refundPartial'])->name('refund-partial');
    });

    // Inventory
    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/{inventory}/edit', [AdminInventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{inventory}', [AdminInventoryController::class, 'update'])->name('inventory.update');
    Route::post('/inventory/{inventory}/restock', [AdminInventoryController::class, 'restock'])->name('inventory.restock');
    Route::get('/inventory/alerts', [AdminInventoryController::class, 'lowStockAlert'])->name('inventory.alerts');
    Route::get('/inventory/export', [AdminInventoryController::class, 'export'])->name('inventory.export');

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Brands
    Route::resource('brands', AdminBrandController::class);

    // Pages
    Route::resource('pages', AdminPageController::class);

    // FAQs
    Route::resource('faqs', AdminChatbotFaqController::class, ['parameters' => ['faq' => 'faq']]);

});
