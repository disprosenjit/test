@extends('layouts.admin')

@section('title', 'Payment #' . $payment->id)

@section('content')
<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold">Payment #{{ $payment->id }}</h1>
        <p class="text-gray-600 mt-1">Order #{{ $payment->order_id }} · {{ $payment->created_at->format('M d, Y H:i') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="/admin/payments" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        @if($payment->proof_file_path)
            <a href="/admin/payments/{{ $payment->id }}/proof" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                <i class="fas fa-image mr-2"></i>View Proof
            </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payment Details -->
    <div class="lg:col-span-2">
        <!-- Order Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Order Information</h2>
            @php
                $order = $payment->order;
            @endphp
            @if($order)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Order ID</p>
                        <a href="/admin/orders/{{ $order->id }}" class="font-semibold text-blue-600 hover:underline">#{{ $order->id }}</a>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Customer</p>
                        <p class="font-semibold">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Order Date</p>
                        <p class="font-semibold">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Order Total</p>
                        <p class="font-semibold">₹{{ number_format($order->total, 2) }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">Order not found</p>
            @endif
        </div>

        <!-- Payment Details -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Payment Details</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Payment Amount</p>
                    <p class="text-2xl font-bold text-green-600">₹{{ number_format($payment->amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Payment Method</p>
                    <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Reference Number</p>
                    <p class="font-mono font-semibold">{{ $payment->transaction_reference ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Transaction ID</p>
                    <p class="font-mono font-semibold">{{ $payment->transaction_reference ?? 'N/A' }}</p>
                </div>
            </div>

            @if($payment->method === 'bank_transfer')
                <div class="mt-4 pt-4 border-t space-y-2">
                    <div>
                        <p class="text-gray-600 text-sm">Bank Account</p>
                        <p class="font-semibold">{{ $payment->bank_account ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">UTR/Cheque Number</p>
                        <p class="font-mono font-semibold">{{ $payment->utr_number ?? 'N/A' }}</p>
                    </div>
                </div>
            @elseif($payment->method === 'upi')
                <div class="mt-4 pt-4 border-t">
                    <p class="text-gray-600 text-sm">UPI Reference</p>
                    <p class="font-mono font-semibold">{{ $payment->upi_reference ?? 'N/A' }}</p>
                </div>
            @endif
        </div>

        <!-- Proof of Payment -->
        @if($payment->proof_file_path)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-bold mb-4">Proof of Payment</h2>
                <div class="bg-gray-100 rounded-lg h-40 flex items-center justify-center">
                    <a href="/admin/payments/{{ $payment->id }}/proof" class="text-blue-600 hover:underline font-semibold">Download Payment Proof</a>
                </div>
            </div>
        @endif

        <!-- Notes -->
        @if($payment->notes)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-bold mb-4">Notes</h2>
                <p class="text-gray-700">{{ $payment->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Status & Actions Sidebar -->
    <div>
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Payment Status</h3>
            
            <div class="mb-4">
                <p class="text-gray-600 text-sm mb-2">Current Status</p>
                <span class="px-3 py-1 rounded text-sm font-semibold
                    @if($payment->status === 'approved') bg-green-100 text-green-800
                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>

            @if($payment->status === 'pending')
                <div class="space-y-3">
                    <!-- Verify Bank Transfer -->
                    @if($payment->method === 'bank_transfer')
                        <form method="GET" action="/admin/payments/{{ $payment->id }}/verify-bank">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold text-sm">
                                <i class="fas fa-check-circle mr-2"></i>Verify Bank Details
                            </button>
                        </form>
                    @endif

                    <!-- Approve -->
                    <form method="POST" action="/admin/payments/{{ $payment->id }}/approve">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-semibold text-sm">
                            <i class="fas fa-check mr-2"></i>Approve Payment
                        </button>
                    </form>

                    <!-- Reject -->
                    <form method="POST" action="/admin/payments/{{ $payment->id }}/reject">
                        @csrf
                        <button type="submit" onclick="return confirm('Reject this payment?')" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-semibold text-sm">
                            <i class="fas fa-times mr-2"></i>Reject Payment
                        </button>
                    </form>
                </div>
            @else
                <div class="text-gray-600 text-sm">
                    <p class="mb-2">Status last updated:</p>
                    <p class="font-semibold">{{ $payment->updated_at->format('M d, Y H:i') }}</p>
                </div>
            @endif
        </div>

        <!-- Customer Info -->
        @if($order)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">Customer</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-sm">Name</p>
                        <p class="font-semibold">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="font-semibold text-sm">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Phone</p>
                        <p class="font-semibold">{{ $order->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>
                <a href="/admin/orders/{{ $order->id }}" class="block mt-4 text-blue-600 hover:underline text-sm">
                    View Order Details →
                </a>
            </div>
        @endif

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Timeline</h3>
            <div class="space-y-3">
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-200">
                            <i class="fas fa-plus text-gray-600"></i>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">Created</p>
                        <p class="text-gray-600 text-xs">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                @if($payment->updated_at != $payment->created_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full {{ $payment->status === 'approved' ? 'bg-green-200' : ($payment->status === 'rejected' ? 'bg-red-200' : 'bg-blue-200') }}">
                                <i class="fas fa-{{ $payment->status === 'approved' ? 'check' : ($payment->status === 'rejected' ? 'times' : 'clock') }} {{ $payment->status === 'approved' ? 'text-green-600' : ($payment->status === 'rejected' ? 'text-red-600' : 'text-blue-600') }}"></i>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-sm">{{ ucfirst($payment->status) }}</p>
                            <p class="text-gray-600 text-xs">{{ $payment->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
