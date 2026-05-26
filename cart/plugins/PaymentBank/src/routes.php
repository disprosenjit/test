<?php

use Illuminate\Support\Facades\Route;
use Plugins\PaymentBank\Controllers\BankTransferPaymentController;

Route::middleware('auth')->group(function () {
    Route::prefix('bank-transfer')->name('bank-transfer.')->group(function () {
        Route::get('/{order}', [BankTransferPaymentController::class, 'show'])->name('show');
    });
});
