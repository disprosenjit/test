@extends('layouts.app')

@section('title', 'Payment Failed - Please Try Again')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-times text-red-600 text-3xl"></i>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Failed</h1>
        <p class="text-gray-600 mb-6">Unfortunately, your payment could not be processed.</p>

        <!-- Error Message -->
        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 mb-8 text-left">
            <h2 class="font-bold text-red-800 mb-2">Error Details</h2>
            <p class="text-red-700">{{ $message ?? 'An error occurred during payment processing.' }}</p>
        </div>

        <!-- Common Issues -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 text-left">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Common Reasons for Payment Failure:</h2>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-start gap-3">
                    <i class="fas fa-credit-card text-blue-600 mt-1 flex-shrink-0"></i>
                    <span><strong>Insufficient funds:</strong> Please verify your card has enough balance</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-calendar text-blue-600 mt-1 flex-shrink-0"></i>
                    <span><strong>Expired card:</strong> Check if your card expiry date is correct</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-lock text-blue-600 mt-1 flex-shrink-0"></i>
                    <span><strong>Security issue:</strong> Your bank may have declined the transaction for security reasons</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-code text-blue-600 mt-1 flex-shrink-0"></i>
                    <span><strong>Incorrect CVV/ZIP:</strong> Please verify your card details are correct</span>
                </li>
            </ul>
        </div>

        <!-- What to Do -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">What You Can Do:</h2>
            <ul class="space-y-2 text-left text-gray-700">
                <li class="flex items-start gap-3">
                    <i class="fas fa-arrow-right text-blue-600 mt-1 flex-shrink-0"></i>
                    <span>Try again with the same card</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-arrow-right text-blue-600 mt-1 flex-shrink-0"></i>
                    <span>Use a different card</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-arrow-right text-blue-600 mt-1 flex-shrink-0"></i>
                    <span>Contact your bank to resolve any blocks</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-arrow-right text-blue-600 mt-1 flex-shrink-0"></i>
                    <span>
                        <a href="mailto:support@example.com" class="text-blue-600 hover:underline">Contact our support team</a>
                        for additional assistance
                    </span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('checkout.index') }}" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Return to Checkout
            </a>
            <a href="{{ route('cart.index') }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition flex items-center justify-center gap-2">
                <i class="fas fa-shopping-cart"></i>
                Back to Cart
            </a>
            <a href="/" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition flex items-center justify-center gap-2">
                <i class="fas fa-home"></i>
                Continue Shopping
            </a>
        </div>

        <!-- Support Contact -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-sm text-gray-600">
            <p>Need help? Contact us at <strong>support@example.com</strong> or call <strong>1-800-SUPPORT</strong></p>
        </div>
    </div>
</div>
@endsection
