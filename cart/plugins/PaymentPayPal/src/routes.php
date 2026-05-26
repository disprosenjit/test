<?php

use Illuminate\Support\Facades\Route;
use Plugins\PaymentPayPal\Controllers\PayPalPaymentController;

Route::middleware('auth')->group(function () {
    Route::prefix('paypal')->name('paypal.')->group(function () {
        Route::get('/checkout/{order}', [PayPalPaymentController::class, 'checkout'])->name('checkout');
        Route::post('/initiate/{order}', [PayPalPaymentController::class, 'initiate'])->name('initiate');
        Route::post('/capture/{order}', [PayPalPaymentController::class, 'capture'])->name('capture');
        Route::get('/success/{order}', [PayPalPaymentController::class, 'success'])->name('success');
        Route::get('/cancel/{order}', [PayPalPaymentController::class, 'cancel'])->name('cancel');
    });
});
