<?php

use Illuminate\Support\Facades\Route;
use Plugins\PaymentStripe\Controllers\StripePaymentController;

Route::middleware('auth')->group(function () {
    Route::prefix('card-payment')->name('card-payment.')->group(function () {
        Route::get('/{order}', [StripePaymentController::class, 'show'])->name('show');
        Route::post('/{order}', [StripePaymentController::class, 'process'])->name('process');
    });
});
