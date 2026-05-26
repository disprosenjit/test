@extends('layouts.app')

@section('title', 'Payment Successful - Order Confirmed')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-check text-green-600 text-3xl"></i>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
        <p class="text-gray-600 mb-8">Your order has been confirmed and payment was processed successfully.</p>

        <!-- Order Details -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 text-left">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Order Details</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-700">Order ID:</span>
                    <span class="font-semibold text-blue-600">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Total Amount Paid:</span>
                    <span class="font-semibold">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Payment Method:</span>
                    <span class="font-semibold">
                        <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                        Card ending in {{ $charge->stripeCard?->last_four ?? 'XXXX' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Transaction ID:</span>
                    <span class="font-mono text-sm">{{ substr($charge->stripe_payment_intent_id, 0, 30) }}...</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Date:</span>
                    <span class="font-semibold">{{ now()->format('M d, Y \a\t g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Charge Details -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8 text-left">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Charge Details</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Charge ID:</span>
                    <span class="font-mono">{{ $charge->stripe_charge_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full font-semibold">
                        {{ ucfirst($charge->status) }}
                    </span>
                </div>
                @if($charge->charged_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Processed At:</span>
                    <span>{{ $charge->charged_at->format('M d, Y g:i A') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">What's Next?</h2>
            <ul class="space-y-2 text-left text-gray-700">
                <li class="flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-600 mt-1 flex-shrink-0"></i>
                    <span>We've sent a confirmation email to <strong>{{ $order->user->email }}</strong></span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-box text-blue-600 mt-1 flex-shrink-0"></i>
                    <span>Your order will be prepared and shipped within 2-3 business days</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-truck text-blue-600 mt-1 flex-shrink-0"></i>
                    <span>You'll receive a tracking number via email when your order ships</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('orders.show', $order->id) }}" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition flex items-center justify-center gap-2">
                <i class="fas fa-receipt"></i>
                View Order Details
            </a>
            <a href="{{ route('orders.invoice', $order->id) }}" class="flex-1 px-6 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 font-semibold transition flex items-center justify-center gap-2">
                <i class="fas fa-file-pdf"></i>
                Download Invoice
            </a>
            <a href="/" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition flex items-center justify-center gap-2">
                <i class="fas fa-home"></i>
                Continue Shopping
            </a>
        </div>

        <!-- Security Note -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-sm text-gray-600">
            <i class="fas fa-lock text-green-600 mr-2"></i>
            All payment information is encrypted and securely processed through Stripe.
        </div>
    </div>
</div>
@endsection
