@extends('layouts.admin')

@section('title', 'Verify Bank Transfer - Payment #' . $payment->id)

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold">Verify Bank Transfer</h1>
        <p class="text-gray-600 mt-1">Order #{{ $payment->order_id }} · {{ $payment->created_at->format('M d, Y H:i') }}</p>
    </div>
    <a href="/admin/payments/{{ $payment->id }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
        <i class="fas fa-arrow-left mr-2"></i>Back to Payment
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Bank Details Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Bank Transfer Details</h2>

        <div class="space-y-4 mb-6">
            <!-- Order & Payment Info -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h3 class="font-bold text-blue-900 mb-3">Payment Information</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-blue-700 text-xs">Order ID</p>
                        <p class="font-semibold">{{ $payment->order_id }}</p>
                    </div>
                    <div>
                        <p class="text-blue-700 text-xs">Amount</p>
                        <p class="font-semibold text-lg text-green-600">₹{{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-blue-700 text-xs">Payment Method</p>
                        <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</p>
                    </div>
                    <div>
                        <p class="text-blue-700 text-xs">Payment ID</p>
                        <p class="font-mono font-semibold text-xs">{{ $payment->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Submitted Details -->
            <div class="border rounded-lg p-4">
                <h3 class="font-bold mb-3 text-gray-900">Submitted Bank Details</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600 text-xs">Bank Account</p>
                        <p class="font-mono font-semibold">{{ $payment->bank_account ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs">UTR/Cheque Number</p>
                        <p class="font-mono font-semibold">{{ $payment->utr_number ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-xs">Transaction Reference</p>
                        <p class="font-mono font-semibold">{{ $payment->transaction_reference ?? 'Not provided' }}</p>
                    </div>
                    @if($payment->proof)
                        <div>
                            <p class="text-gray-600 text-xs">Payment Proof</p>
                            <p class="text-blue-600 text-sm">
                                <a href="/admin/payments/{{ $payment->id }}/proof" target="_blank" class="hover:underline">
                                    <i class="fas fa-download mr-1"></i>Download Proof
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Verification Checklist -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-bold text-yellow-900 mb-3">Verification Checklist</h3>
                <div class="space-y-2 text-sm text-yellow-800">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" class="w-4 h-4"> Amount matches (₹{{ number_format($payment->amount, 2) }})
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" class="w-4 h-4"> UTR/Reference number verified
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" class="w-4 h-4"> Account details correct
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" class="w-4 h-4"> Transfer date acceptable
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 pt-4 border-t">
            <form method="POST" action="/admin/payments/{{ $payment->id }}/approve" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-semibold">
                    <i class="fas fa-check mr-2"></i>Approve Payment
                </button>
            </form>

            <form method="POST" action="/admin/payments/{{ $payment->id }}/reject" class="flex-1">
                @csrf
                <button type="submit" onclick="return confirm('Reject this payment?')" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-semibold">
                    <i class="fas fa-times mr-2"></i>Reject Payment
                </button>
            </form>
        </div>
    </div>

    <!-- Instructions & Reference -->
    <div>
        <!-- Verification Instructions -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Verification Instructions</h3>
            
            <div class="space-y-4 text-sm">
                <div>
                    <p class="font-semibold text-gray-900 mb-2">1. Verify Amount</p>
                    <p class="text-gray-600">Confirm the transferred amount matches the payment amount of ₹{{ number_format($payment->amount, 2) }}</p>
                </div>

                <div>
                    <p class="font-semibold text-gray-900 mb-2">2. Check Reference Number</p>
                    <p class="text-gray-600">Validate the UTR or cheque number with the bank to ensure the transaction is genuine</p>
                </div>

                <div>
                    <p class="font-semibold text-gray-900 mb-2">3. Confirm Account Details</p>
                    <p class="text-gray-600">Cross-check the sender's bank account details from the proof document</p>
                </div>

                <div>
                    <p class="font-semibold text-gray-900 mb-2">4. Review Proof Document</p>
                    <p class="text-gray-600">Examine the payment proof screenshot/receipt for authenticity</p>
                </div>

                <div>
                    <p class="font-semibold text-gray-900 mb-2">5. Make Decision</p>
                    <p class="text-gray-600">Click Approve if verified, or Reject if there are discrepancies</p>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        @if($payment->order)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4">Order Details</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Order ID</p>
                        <a href="/admin/orders/{{ $payment->order->id }}" class="text-blue-600 hover:underline font-semibold">
                            #{{ $payment->order->id }}
                        </a>
                    </div>
                    <div>
                        <p class="text-gray-600">Customer</p>
                        <p class="font-semibold">{{ $payment->order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-semibold text-xs">{{ $payment->order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Order Total</p>
                        <p class="text-lg font-bold text-green-600">₹{{ number_format($payment->order->total, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Order Date</p>
                        <p class="font-semibold">{{ $payment->order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <a href="/admin/orders/{{ $payment->order->id }}" class="block mt-4 text-blue-600 hover:underline text-sm font-semibold">
                    View Full Order →
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
